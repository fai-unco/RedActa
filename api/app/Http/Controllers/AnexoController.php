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
        //Check if user is authorized to create anexo in the document
        $this->authorize('create', [Anexo::class, $document]);
        $anexo = new Anexo();
        $anexo->set($data['index'], $data['title'], $data['subtitle'], $data['content'], $document, $file);
        return response()->json([
            'status' => 201,
            'message' => 'OK',
            'data' => [
                'id' => $anexo->id
            ]       
        ], 201);    
    
    }

    /**
     * Display the specified resource.
     *
     * @param App\Models\Anexo  $anexo
     * @return \Illuminate\Http\Response
     */
    public function show(Anexo $anexo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param App\Models\Anexo  $anexo
     * @return \Illuminate\Http\Response
     */
    public function edit(Anexo $anexo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UpdateAnexoRequest  $request
     * @param App\Models\Anexo  $anexo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAnexoRequest $request, Anexo $anexo)
    {
        $data = $request->validated();
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
        // Check if user is authorized to update anexo in the document
        $this->authorize('update', $anexo);
        $anexo->set($data['index'], $data['title'], $data['subtitle'], $data['content'], $anexo->document, $file);
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => [
                'id' => $anexo->id
            ]       
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param App\Models\Anexo  $anexo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Anexo $anexo)
    {
        // Check if user is authorized to delete anexo in the document
        $this->authorize('delete', $anexo);
        $anexo->delete();
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => [
                'id' => $anexo->id
            ]       
        ]);        
    }
}