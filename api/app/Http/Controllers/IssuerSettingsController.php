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
     * @param \App\Models\IssuerSettings $issuerSettings
     * @return \Illuminate\Http\Response
     */
    public function show(IssuerSettings $issuerSettings)
    {
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $issuerSettings           
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\IssuerSettings $issuerSettings
     * @return \Illuminate\Http\Response
     */
    public function edit(IssuerSettings $issuerSettings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UpdateIssuerSettingsRequest  $request
     * @param \App\Models\IssuerSettings $issuerSettings
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateIssuerSettingsRequest $request, IssuerSettings $issuerSettings)
    {
        $this->authorize('update', $issuerSettings);
        $validatedData = $request->validated();
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
     * @param \App\Models\IssuerSettings $issuerSettings
     * @return \Illuminate\Http\Response
     */
    public function destroy(IssuerSettings $issuerSettings)
    {
        $this->authorize('delete', $issuerSettings);
        $issuerSettings->delete();
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $issuerSettings           
        ]);
    }
}



