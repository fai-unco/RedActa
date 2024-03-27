<?php

namespace App\Http\Controllers;

use App\Models\Signature;
use App\Models\DocumentSharedAccess;
use App\Models\Document;
use App\Models\Stamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SignatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $validatedData['redacta_user_id'] = $request->user()->id;
        //try {  
            $stamp = Stamp::find($validatedData['stamp_id']);
            if ($stamp->redactaUser->id != $request->user()->id) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Sello inválido'        
                ], 422);
            }
            $document = Document::find($validatedData['document_id']);
            if (!$this->userHasAccessToDocument($request->user()->id, $document)) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Documento inválido'        
                ], 422);
            }
            $signature = Signature::create($validatedData);
            return response()->json([
                'status' => 201,
                'message' => 'OK',
                'data' => $signature         
            ]);
        /*} catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
        }*/
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
            $signature = Signature::find($id);
            if (!$signature || !$this->userHasAccessToDocument($request->user()->id, $signature->document)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Recurso inexistente'        
                ], 404);
            }
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $signature           
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
            $signature = Signature::find($id);
            if (!$signature || $signature->redactaUser->id != $request->user()->id) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Recurso inexistente'        
                ], 404);
            }
            $signature->delete();
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $signature           
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
        }
    }

    private function userHasAccessToDocument($loggedInUserId, $document){
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

    private function validateRequest($request){
        $validator = Validator::make($request->all(), [
            'document_id' => 'required|numeric|exists:documents,id',
            'stamp_id' => 'required|numeric|exists:stamps,id',
        ], [
            'required' => 'El campo :attribute es requerido',
            'numeric' => 'El campo :attribute debe ser un número entero',
        ], [
            'document_id' => '"Documento"',
            'stamp_id' => '"Sello"',
        ])->stopOnFirstFailure(true);
        $validator->validate();
        return $validator->validated();
    }
}
