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
        //Check if issuer settings already exist for the issuer
        $existingSettings = IssuerSettings::where('issuer_id', $validatedData['issuer_id'])->first();
        if ($existingSettings) {
            return response()->json([
                'status' => 422,
                'message' => 'Settings already exist for this issuer'        
            ], 422);
        }
        $issuerSetting = new IssuerSettings(); 
        $issuerSetting->set($validatedData);
        return response()->json([
            'status' => 201,
            'message' => 'OK',
            'data' => $issuerSetting           
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\IssuerSettings $issuerSetting
     * @return \Illuminate\Http\Response
     */
    public function show(IssuerSettings $issuerSetting)
    {
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $issuerSetting           
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\IssuerSettings $issuerSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(IssuerSettings $issuerSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UpdateIssuerSettingsRequest  $request
     * @param \App\Models\IssuerSettings $issuerSetting
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateIssuerSettingsRequest $request, IssuerSettings $issuerSetting)
    {
        $this->authorize('update', $issuerSetting);
        $validatedData = $request->validated();
        $issuerSetting->update($validatedData);
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $issuerSetting           
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\IssuerSettings $issuerSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(IssuerSettings $issuerSetting)
    {
        $this->authorize('delete', $issuerSetting);
        $issuerSetting->delete();
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $issuerSetting           
        ]);
    }
}



