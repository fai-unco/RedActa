<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IssuerSettings;
use Illuminate\Support\Facades\Validator;


class IssuerSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {    
            if($request->has('issuer_id')){
                $results = IssuerSettings::where('issuer_id', $request->query('issuer_id'))->first();
            } else {
                $results = IssuerSettings::all();
            }
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
        $this->validateRequest($request);
        try {
            $issuerSettings = new IssuerSettings(); 
            $validatedData = $this->validateRequest($request);
            $issuerSettings->set($validatedData);
            return response()->json([
                'status' => 201,
                'message' => 'OK',
                'data' => $issuerSettings           
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
            $issuerSettings = IssuerSettings::find($id);
            if(!$issuerSettings){
                return response()->json([
                    'status' => 404,
                    'message' => 'El recurso al que desea acceder no existe'        
                ], 404);
            }
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $issuerSettings           
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
            $issuerSettings = IssuerSettings::find($id);
            if(!$issuerSettings){
                return response()->json([
                    'status' => 404,
                    'message' => 'El recurso al que desea acceder no existe'        
                ], 404);
            }
            $issuerSettings->set($validatedData);
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $issuerSettings           
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
        $validator = Validator::make($request->all(), [
            'issuer_id' => 'required|numeric|exists:issuers,id',
            'operative_section_beginning_id' => 'required|numeric|exists:operative_section_beginnings,id',
            'true_copy_stamp_id' => 'sometimes|numeric|exists:stamps,id'
        ], [
            'required' => 'El campo :attribute es requerido',
            'numeric' => 'El campo :attribute debe ser un número'
        ], [
            'issuer_id' => '"Emisor"',
            'operative_section_beginning_id' => '"Inicio de sección operativa"',
            'true_copy_stamp_id' => '"Sello en copia fiel"'
        ])->stopOnFirstFailure(true);
        $validator->validate();
        return $validator->validated();
    }
}



