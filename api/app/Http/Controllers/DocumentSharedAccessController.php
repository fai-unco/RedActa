<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentSharedAccess;
use App\Models\Document;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ShareDocumentMailService;



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
                if (!$this->userHasAccessToDocument($request->user()->id, Document::find($request->query('document_id')))) {
                    return response()->json([
                        'status' => 422,
                        'message' => 'Documento inválido'        
                    ], 422);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateRequest($request);
        try {
            $document = Document::find($validatedData['document_id']);
            if ($document->redactaUser->id != $request->user()->id) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Documento inválido'        
                ], 422);
            }
            if ($this->userHasAccessToDocument($validatedData['redacta_user_id'], $document)) {
                return response()->json([
                    'status' => 409,
                    'message' => 'La cuenta seleccionada ya tiene actualmente permisos de acceso a este documento'        
                ], 409);
            }
            if (!isset($validatedData['access_mode_id'])) {
                $validatedData['access_mode_id'] = 1;
            } 
            $documentSharedAccess = DocumentSharedAccess::create($validatedData);
            Mail::to($documentSharedAccess->redactaUser->email)
                ->send(new ShareDocumentMailService($document->id, $request->user()));
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
            if (!$documentSharedAccess || 
                !$this->userHasAccessToDocument($request->user()->id, $documentSharedAccess->document)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Recurso inexistente'        
                ], 404);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
                ], 404);
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
            if (!$documentSharedAccess || 
                ($documentSharedAccess->redactaUser->id != $request->user()->id &&
                    $documentSharedAccess->document->redactaUser->id != $request->user()->id)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Recurso inexistente'        
                ], 404);
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
                ], 404);
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

    private function validateRequest($request) {
        if ($request->isMethod('post')) {
            $firstRule = 'required';
        } else {
            $firstRule = 'sometimes';
        }
        $validator = Validator::make($request->all(), [
            'redacta_user_id' => $firstRule.'|numeric|exists:redacta_users,id',
            'document_id' => $firstRule.'|numeric|exists:documents,id',
            'access_mode_id' => 'sometimes|numeric|exists:access_modes,id',
        ], [
            'required' => 'El campo :attribute es requerido',
            'numeric' => 'El campo :attribute debe ser numérico',
        ], [
            'redacta_user_id' => '"Usuario"',
            'document_id' => '"Documento"',
            'access_mode_id' => '"Modo de acceso"'
        ])->stopOnFirstFailure(true);
        $validator->validate();
        return $validator->validated();
    }

    private function userHasAccessToDocument($loggedInUserId, $document) {
        if ($document->redactaUser->id != $loggedInUserId) {
            $documentSharedAccess = DocumentSharedAccess::where([
                ['redacta_user_id', '=', $loggedInUserId],
                ['document_id', '=', $document->id]
            ])->get();
            if (count($documentSharedAccess) == 0) {
                return false;
            }
        }
        return true; 
    }
}
