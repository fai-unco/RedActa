<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issuer;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreIssuerRequest;
use App\Http\Requests\UpdateIssuerRequest;


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
     * @param  App\Http\Requests\StoreIssuerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIssuerRequest $request)
    {
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
     * @param  App\Http\Requests\UpdateIssuerRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateIssuerRequest $request, $id)
    {
        $issuer = Issuer::find($id);
        if(!$issuer){
            return response()->json([
                'status' => 404,
                'message' => 'El recurso al que desea acceder no existe'        
            ], 404);
        }
        $validatedData = $request->validated();
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
}