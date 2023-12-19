<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stamp;
use Illuminate\Support\Facades\Validator;

class StampController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {    
            $results = Stamp::all();
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $results           
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
        array_push($validatedData, ['redacta_user_id' => $request->user()->id]);
        try {  
            $stamp = Stamp::create($validatedData);
            return response()->json([
                'status' => 201,
                'message' => 'OK',
                'data' => $stamp          
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
        $validatedData = $this->validateRequest($request);
        try { 
            $stamp = Stamp::find($id);
            if (!$stamp) {
                return response()->json([
                    'status' => 404,
                    'message' => 'El recurso al que desea acceder no existe'        
                ], 404);
            }
            $stamp->update($validatedData);
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $stamp           
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
        try { 
            $stamp = Stamp::find($id);
            if (!$stamp) {
                return response()->json([
                    'status' => 404,
                    'message' => 'El recurso al que desea acceder no existe'        
                ], 404);
            }
            $stamp->delete();
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $stamp           
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
        }
    }

    private function validateRequest($request){
        $presence = 'required';
        if ($request->isMethod('patch')) {
            $presence = 'sometimes';
        }
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string',
            'position' => 'sometimes|string',
            'issuer_id'=> $presence.'|numeric|exists:issuers,id',
        ], [
            'required' => 'El campo :attribute es requerido',
            'string' => 'El campo :attribute debe ser un string',
            'issuer_id.exists' => 'El emisor especificado no existe'
        ], [
            'full_name' => '"Nombre completo"',
            'position' => '"Cargo"',
            'issuer_id' => '"Emisor"'
        ])->stopOnFirstFailure(true);
        $validator->validate();
        return $validator->validated();
    }
}
