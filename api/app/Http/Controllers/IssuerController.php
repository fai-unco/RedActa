<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issuer;
use Illuminate\Support\Facades\Validator;


class IssuerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $issuers = Issuer::all();
        return response()->json([
            'status' => 200,
            'description' => 'OK',
            'data' => $issuers       
        ]);
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
        $issuer = new Issuer(); 
        $validatedData = $this->validateRequest($request);
        $issuer->set($validatedData);
        return response()->json([
            'status' => 201,
            'message' => 'OK',
            'data' => $issuer           
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $issuer = Issuer::find($id);
        if(!$issuer){
            return response()->json([
                'status' => 404,
                'message' => 'El recurso al que desea acceder no existe'        
            ], 404);
        }
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $issuer           
        ]);
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
        $issuer = Issuer::find($id);
        if(!$issuer){
            return response()->json([
                'status' => 404,
                'message' => 'El recurso al que desea acceder no existe'        
            ], 404);
        }
        $validatedData = $this->validateRequest($request);
        $issuer->set($validatedData);
        return response()->json([
            'status' => 201,
            'message' => 'OK',
            'data' => $issuer           
        ]);
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
            'description' => 'required|string',
            'phone' => 'sometimes|numeric|nullable',
            'address' => 'sometimes|string|nullable',
            'postal_code' => 'sometimes|string|nullable',
            'city' => 'sometimes|string|nullable',
            'state' => 'sometimes|string|nullable',
            'website_url' => 'sometimes|string|nullable',
            'email' => 'sometimes|string|nullable'
        ], [
            'required' => 'El campo :attribute es requerido',
            'numeric' => 'El campo :attribute debe ser un número',
            'string' => 'El campo :attribute debe ser un string'
        ], [
            'description' => '"Nombre del emisor"',
            'phone' => '"Teléfono"',
            'address' => '"Dirección"',
            'postal_code' => '"Código postal"',
            'city' => '"Ciudad"',
            'state' => '"Provincia"',
            'website_url' => '"Url al sitio web"',
            'email' => '"Dirección de correo electrónico"',
        ])->stopOnFirstFailure(true);
        $validator->validate();
        return $validator->validated();
    }
}