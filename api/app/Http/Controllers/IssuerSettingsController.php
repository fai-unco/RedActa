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
        $this->authorize('create', IssuerSettings::class);
        $validatedData = $request->validated();
        $issuerSettings = new IssuerSettings(); 
        $issuerSettings->set($validatedData);
        return response()->json([
            'status' => 201,
            'message' => 'OK',
            'data' => $issuerSettings           
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
        $validatedData = $request->validated();
        $issuerSettings = IssuerSettings::find($id);
        if(!$issuerSettings){
            return response()->json([
                'status' => 404,
                'message' => 'El recurso al que desea acceder no existe'        
            ], 404);
        }
        $this->authorize('update', $issuerSettings);
        $issuerSettings->set($validatedData);
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $issuerSettings           
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
        $issuerSettings = IssuerSettings::find($id);
        if(!$issueSettings){
            return response()->json([
                'status' => 404,
                'message' => 'El recurso al que desea acceder no existe'        
            ], 404);
        }
        $this->authorize('delete', $issuerSettings);
        $issuerSettings->delete();
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $issuerSettings           
        ]);
    }
}



