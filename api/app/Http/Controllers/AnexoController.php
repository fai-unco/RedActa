<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Document;
use App\Models\Anexo;
use App\Models\DocumentSharedAccess;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreAnexoRequest;
use App\Http\Requests\UpdateAnexoRequest;


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
     * @param  App\Http\Requests\StoreAnexoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAnexoRequest $request)
    {
        try {
            $data = $request->validated();
            $file = File::find($data['file_id']);
            // Check if the file exists and belongs to the user
            if (!$file || $file->redactaUser->id != $request->user()->id) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Archivo inválido'        
                ], 422);
            }
            $document = Document::find($data['document_id']);
            // Check if the document exists and can be accessed by the user
            if (!$document->isAccessibleToRedactaUser($request->user(), 1)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'No tiene los permisos necesarios para realizar la operación'        
                ], 422);
            }
            $anexo = new Anexo();
            $anexo->set($data['index'], $data['title'], $data['subtitle'], $data['content'], $document, $file);
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
     * @param  App\Http\Requests\UpdateAnexoRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAnexoRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $anexo = Anexo::find($id);
            if (!$anexo) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Recurso inexistente',       
                ], 404);  
            }
            // Check if the user has access to the document
            if (!$anexo->document->isAccessibleToRedactaUser($request->user(), 1)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'No tiene los permisos necesarios para realizar la operación'        
                ], 403);
            }

            if (isset($data['file_id'])) {
                // Check if the file exists and belongs to the user
                $file = File::find($data['file_id']);
                if ($file->redactaUser->id != $request->user()->id) {
                    return response()->json([
                        'status' => 422,
                        'message' => 'Archivo inválido'        
                    ], 422);
                }
            }
            $anexo->set($data['index'], $data['title'], $data['subtitle'], $data['content'], $anexo->document, $file);
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
            if(!$anexo){
                return response()->json([
                    'status' => 404,
                    'message' => 'Recurso inexistente',       
                ], 404);     
            } 
            // Check if the user has access to the document
            if (!$anexo->document->isAccessibleToRedactaUser($request->user(), 1)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'No tiene los permisos necesarios para realizar la operación'        
                ], 403);            
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
}