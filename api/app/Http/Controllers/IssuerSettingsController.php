<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IssuerSettings;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreIssuerSettingsRequest;
use App\Http\Requests\UpdateIssuerSettingsRequest;


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
     * @param  App\Http\Requests\StoreIssuerSettingsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIssuerSettingsRequest $request)
    {
        try {
            $issuerSettings = new IssuerSettings(); 
            $validatedData = $request->validated();
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
     * @param  App\Http\Requests\UpdateIssuerSettingsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateIssuerSettingsRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
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
}



