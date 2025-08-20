<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\OperativeSectionBeginning;
use App\Models\Issuer;
use App\Http\Requests\StoreOperativeSectionBeginningRequest;
use App\Http\Requests\UpdateOperativeSectionBeginningRequest;


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
     * @param  App\Http\Requests\StoreOperativeSectionBeginningRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOperativeSectionBeginningRequest $request)
    {  
        $this->authorize('create', OperaticeSectionBeginning::class);
        $validatedData = $request->validated();
        $operativeSectionBeginning = OperativeSectionBeginning::create($validatedData);
        return response()->json([
            'status' => 201,
            'message' => 'OK',
            'data' => $operativeSectionBeginning           
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\OperativeSectionBeginning $operativeSectionBeginning
     * @return \Illuminate\Http\Response
     */
    public function show(OperativeSectionBeginning $operativeSectionBeginning)
    {  
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $operativeSectionBeginning           
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\OperativeSectionBeginning $operativeSectionBeginning
     * @return \Illuminate\Http\Response
     */
    public function edit(OperativeSectionBeginning $operativeSectionBeginning)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UpdateOperativeSectionBeginningRequest  $request
     * @param \App\Models\OperativeSectionBeginning $operativeSectionBeginning
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOperativeSectionBeginningRequest $request, OperativeSectionBeginning $operativeSectionBeginning)
    {
        $this->authorize('update', $operativeSectionBeginning);
        $validatedData = $request->validated();
        $operativeSectionBeginning->update($validatedData);
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $operativeSectionBeginning           
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\OperativeSectionBeginning $operativeSectionBeginning
     * @return \Illuminate\Http\Response
     */
    public function destroy(OperativeSectionBeginning $operativeSectionBeginning)
    {
        $this->authorize('delete', $operativeSectionBeginning);
        $operativeSectionBeginning->delete();
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $operativeSectionBeginning         
        ]);
    }
}


