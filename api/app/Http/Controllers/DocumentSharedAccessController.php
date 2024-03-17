<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentSharedAccess;
use App\Models\Document;
use Illuminate\Support\Facades\Validator;


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
            $documentSharedAccess = DocumentSharedAccess::create($validatedData);
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
        //
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

    private function validateRequest($request) {
        $validator = Validator::make($request->all(), [
            'redacta_user_id' => 'required|numeric|exists:redacta_users,id',
            'document_id' => 'required|numeric|exists:documents,id',
        ], [
            'required' => 'El campo :attribute es requerido',
            'numeric' => 'El campo :attribute debe ser numérico',
        ], [
            'redacta_user_id' => '"Usuario"',
            'document_id' => '"Documento"'
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
