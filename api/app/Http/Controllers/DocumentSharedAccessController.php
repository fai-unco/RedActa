<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentSharedAccess;
use App\Models\Document;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ShareDocumentMailService;
use App\Http\Requests\StoreDocumentSharedAccessRequest;
use App\Http\Requests\UpdateDocumentSharedAccessRequest;


class DocumentSharedAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {       
            if ($request->has('document_id')) {
                $document = Document::find($request->query('document_id'));
                if (!$document->isAccessibleToRedactaUser($request->user(), 2)) {
                    return response()->json([
                        'status' => 403,
                        'message' => 'No tiene los permisos necesarios para realizar la operación'        
                    ], 403);
                }
                $result = DocumentSharedAccess::where('document_id', $request->query('document_id'))->get();
            } else {
                $result = $request->user()->documentsSharedAccesses;
            }
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $result        
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreDocumentSharedAccessRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDocumentSharedAccessRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $document = Document::find($validatedData['document_id']);
            if (!$document) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Recurso inexistente'
                ], 404);
            }
            if (!$document->isAccessibleToRedactaUser($request->user(), 1)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'No tiene los permisos necesarios para realizar la operación'        
                ], 403);
            }

            $validatedData = $this->setDocumentSharedAccessableFields($validatedData, $request);

            // Check if exists a document shared access for the same user/group
            if ($document->documentSharedAccesses()
                    ->where('document_shared_accessable_type', $validatedData['document_shared_accessable_type'])
                    ->where('document_shared_accessable_id', $validatedData['document_shared_accessable_id'])
                    ->exists()) {
                return response()->json([
                    'status' => 409,
                    'message' => 'Ya existe un acceso compartido al documento para el usuario o grupo seleccionado'        
                ], 409);
            }
            
            $documentSharedAccess = DocumentSharedAccess::create($validatedData);

            // Notify the user or group members about the shared access
            $usersToNotify = [];
            if ($validatedData['document_shared_accessable_type'] == 'App\Models\RedactaUser') {
                $usersToNotify[] = $documentSharedAccess->document_shared_accessable;
            } else {
                foreach ($documentSharedAccess->documentSharedAccessable->redactaUsers as $user) {
                    $usersToNotify[] = $user;
                }
            }

            foreach ($usersToNotify as $user) {
                if ($user->id != $request->user()->id) {
                    Mail::to($user->email)
                        ->send(new ShareDocumentMailService($document->id, $request->user()));
                }
            }

            return response()->json([
                'status' => 201,
                'message' => 'OK',
                'data' => $documentSharedAccess
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            $documentSharedAccess = DocumentSharedAccess::find($id);
            if (!$documentSharedAccess->document->isAccessibleToRedactaUser($request->user(), 2)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'No tiene los permisos necesarios para realizar la operación'        
                ], 403);
            }
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $documentSharedAccess           
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UpdateDocumentSharedAccessRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDocumentSharedAccessRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            $documentSharedAccess = DocumentSharedAccess::find($id);

            if (!$documentSharedAccess) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Recurso inexistente'
                ], 404);
            }

            if (!$documentSharedAccess->document->isAccessibleToRedactaUser($request->user(), 1)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'No tiene los permisos necesarios para realizar la operación'        
                ], 403);
            }

            $documentSharedAccess->update($validatedData);
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $documentSharedAccess           
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try { 
            $documentSharedAccess = DocumentSharedAccess::find($id);
            if (!$documentSharedAccess) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Recurso inexistente'
                ], 404);
            }
            if (!$documentSharedAccess->document->isAccessibleToRedactaUser($request->user(), 1)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'No tiene los permisos necesarios para realizar la operación'        
                ], 403);
            }
            $documentSharedAccess->delete();
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $documentSharedAccess           
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
        }
    }


    public function notify(Request $request, $id)
    {
        $validatedData = $this->validateRequest($request);
        try {
            $documentSharedAccess = DocumentSharedAccess::find($id);
            if (!$documentSharedAccess) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Recurso inexistente'        
                ], 404);
            }
            if ($documentSharedAccess->document->redactaUser->id != $request->user()->id) {
                return response()->json([
                    'status' => 403,
                    'message' => 'No tiene autorización para realizar esta acción'        
                ], 403);
            }
            Mail::to($documentSharedAccess->redactaUser->email)
                ->send(new ShareDocumentMailService($documentSharedAccess->document->id, $request->user()));
            return response()->json([
                'status' => 200,
                'message' => 'OK'           
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
        }
    }

    /**
     * Set document_shared_accessable_id and document_shared_accessable_type fields.
     *
     * @param array $validatedData
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    private function setDocumentSharedAccessableFields(array $validatedData, Request $request)
    {
        if (!isset($validatedData['access_mode_id'])) {
            $validatedData['access_mode_id'] = 1;
        }
        if (isset($validatedData['resource_id'])) {
            $validatedData['document_shared_accessable_id'] = $validatedData['resource_id'];
        }
        if ($request->has('resource_type')) {
            $validatedData['document_shared_accessable_type'] = $request->input('resource_type') == 'group'
                ? 'App\Models\Group'
                : 'App\Models\RedactaUser';
        }
        return $validatedData;
    }
}
