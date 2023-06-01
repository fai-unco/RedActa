<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Document;
use App\Models\Anexo;
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
            $document = Document::find($request->documentId);
            $file = null;
            if(!$document || $request->user()->id != $document->redactaUser->id){
                return response()->json([
                    'status' => 404,
                    'message' => 'Documento inexistente',       
                ], 404);  
            }
            if($request->fileId){ 
                $file = File::find($request->fileId);
            
                if(!$file || $file->redactaUser->id != $request->user()->id){ // || $request->user()->id != $file->user->id
                    return response()->json([
                        'status' => 404,
                        'message' => 'Archivo inexistente',       
                    ], 404);  
                }  
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
            $anexo =  Anexo::find($id);
            $file = null;
            if(!$anexo || $anexo->document->redactaUser->id != $request->user()->id){
                return response()->json([
                    'status' => 404,
                    'message' => 'Anexo inexistente',       
                ], 404);  
            }
            if($request->fileId){
                $file = File::find($request->fileId);
                if(!$file || $request->user()->id != $file->redactaUser->id){ 
                    return response()->json([
                        'status' => 404,
                        'message' => 'Archivo inexistente',       
                    ], 404);  
                }
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
            if(!$anexo || $anexo->document->redactaUser->id != $request->user()->id){
                return response()->json([
                    'status' => 404,
                    'message' => 'Anexo inexistente',       
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
                'documentId' => 'required|numeric',
                'fileId' => 'required|numeric'
            ], [
                'required' => 'El campo :attribute es requerido',
                'numeric' => 'El campo :attribute debe ser un número',
            ] ,[
                'fileId' => '"seleccionar archivo"'
            ])->stopOnFirstFailure(true);
        $validator->validate();
    }
}

/*$anexo->index = $request->index;
            $anexo->title = $request->title;
            $anexo->subtitle = $request->subtitle;
            $anexo->content = $request->content;
            $anexo->document()->associate($document);
            $anexo->save();*/


            /*$anexo->index = $request->index;
            $anexo->title = $request->title;
            $anexo->subtitle = $request->subtitle;
            $anexo->content = $request->content;
            $anexo->save();
            if($file && $anexo->file != $file){
                if($anexo->file != null){
                    $anexo->file()->update(['anexo_id' => null]);
                }
                $anexo->file()->save($file);
                if($request->fileId){
                $anexo->file()->save($file);
            }
            }*/