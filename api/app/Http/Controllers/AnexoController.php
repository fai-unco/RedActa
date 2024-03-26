<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Document;
use App\Models\Anexo;
use App\Models\DocumentSharedAccess;
use Illuminate\Support\Facades\Validator;



class AnexoController extends Controller
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
        $this->validateRequest($request);
        try {
            $file = File::find($request->file_id);
            if (!$file || $file->redactaUser->id != $request->user()->id) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Archivo inválido'        
                ], 422);
            }
            $document = Document::find($request->document_id);
            if (!$this->userHasAccessToDocument($request->user()->id, $document)) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Documento inválido'        
                ], 422);
            }
            $anexo = new Anexo();
            $anexo->set($request->index, $request->title, $request->subtitle, $request->content, $document, $file);
            return response()->json([
                'status' => 201,
                'message' => 'OK',
                'data' => [
                    'id' => $anexo->id
                ]       
            ], 201);    
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $this->validateRequest($request);
        try {
            $anexo = Anexo::find($id);
            if (!$anexo || !$this->userHasAccessToDocument($request->user()->id, $anexo->document)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Recurso inexistente',       
                ], 404);  
            }
            $file = File::find($request->file_id);
            if (!$file || !$this->userHasAccessToDocument($file->redactaUser->id, $anexo->document)) {
                //|| ($request->user()->id != $file->redactaUser->id) { 
                return response()->json([
                    'status' => 422,
                    'message' => 'Archivo inválido',       
                ], 422);  
            }
            $anexo->set($request->index, $request->title, $request->subtitle, $request->content, $anexo->document, $file);
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => [
                    'id' => $anexo->id
                ]       
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $anexo =  Anexo::find($id);
            if(!$anexo || !$this->userHasAccessToDocument($request->user()->id, $anexo->document)){
                return response()->json([
                    'status' => 404,
                    'message' => 'Recurso inexistente',       
                ], 404);     
            } 
            $anexo->delete();
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => [
                    'id' => $id
                ]       
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
                'index' => 'required|numeric',
                'document_id' => 'required|numeric|exists:documents,id',
                'file_id' => 'required|numeric|exists:files,id'
            ], [
                'required' => 'El campo :attribute es requerido',
                'numeric' => 'El campo :attribute debe ser un número',
            ] ,[
                'file_id' => '"seleccionar archivo"'
            ])->stopOnFirstFailure(true);
        $validator->validate();
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