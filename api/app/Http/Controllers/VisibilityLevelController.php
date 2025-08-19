<?php

namespace App\Http\Controllers;

use App\Models\VisibilityLevel;
use Illuminate\Http\Request;

class VisibilityLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {      
        $results = VisibilityLevel::all();
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $visibilityLevel = VisibilityLevel::find($id);
        if (!$visibilityLevel) {
            return response()->json([
                'status' => 404,
                'message' => 'Recurso inexistente'        
            ], 404);
        }
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $visibilityLevel          
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VisibilityLevel  $visibilityLevel
     * @return \Illuminate\Http\Response
     */
    public function edit(VisibilityLevel $visibilityLevel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VisibilityLevel  $visibilityLevel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VisibilityLevel $visibilityLevel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VisibilityLevel  $visibilityLevel
     * @return \Illuminate\Http\Response
     */
    public function destroy(VisibilityLevel $visibilityLevel)
    {
        //
    }
}
