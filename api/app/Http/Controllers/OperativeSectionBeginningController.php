<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\OperativeSectionBeginning;
use App\Models\Issuer;


class OperativeSectionBeginningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //try {    
            if($request->has('issuer_id')){
                $results = OperativeSectionBeginning::where('issuer_id', $request->query('issuer_id'))->get();
            } else {
                $results = OperativeSectionBeginning::all();
            }
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $results           
            ]);
        /*} catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
        }*/
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
            $operativeSectionBeginning = OperativeSectionBeginning::create($validatedData);
            return response()->json([
                'status' => 201,
                'message' => 'OK',
                'data' => $operativeSectionBeginning           
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {  
            $operativeSectionBeginning = OperativeSectionBeginning::find($id);
            if (!$operativeSectionBeginning) {
                return response()->json([
                    'status' => 404,
                    'message' => 'El recurso al que desea acceder no existe'        
                ], 404);
            }
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $operativeSectionBeginning           
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
            $operativeSectionBeginning = OperativeSectionBeginning::find($id);
            if (!$operativeSectionBeginning) {
                return response()->json([
                    'status' => 404,
                    'message' => 'El recurso al que desea acceder no existe'        
                ], 404);
            }
            $operativeSectionBeginning->update($validatedData);
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $operativeSectionBeginning           
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
    public function destroy($id)
    {
        //
    }

    private function validateRequest($request){
        $presence = 'required';
        if ($request->isMethod('patch')) {
            $presence = 'sometimes';
        }
        $validator = Validator::make($request->all(), [
            'issuer_id' => $presence.'|numeric|exists:issuers,id',
            'content' => $presence.'|string',
        ], [
            'required' => 'El campo :attribute es requerido',
            'numeric' => 'El campo :attribute debe ser un número',
            'string' => 'El campo :attribute debe ser un string',
            'issuer_id.exists' => 'El emisor especificado no existe'
        ], [
            'issuer_id' => '"Emisor"',
            'content' => '"Contenido"',
        ])->stopOnFirstFailure(true);
        $validator->validate();
        return $validator->validated();
    }

}


