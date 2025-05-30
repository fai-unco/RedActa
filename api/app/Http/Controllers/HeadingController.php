<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Heading;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreHeadingRequest;
use App\Http\Requests\UpdateHeadingRequest;


class HeadingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /* Accepted query parameters:
            - include_file (boolean): send data of the associated file in response
            - issuer_id (number): the id of the issuer whose headings will be returned
        */  
        try {       
            $searchParameters = [];
            if($request->boolean('include_file', false)){
                $headings = Heading::with(['file']);
            } else {
                $headings = Heading::query();
            }
            if($request->has('issuer_id')){
                $headings = $headings->where('issuer_id', $request->query('issuer_id'));
            }
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $headings->get()          
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
     * @param  App\Http\Requests\StoreHeadingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHeadingRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $heading = new Heading(); 
            $heading->set($validatedData);
            return response()->json([
                'status' => 201,
                'message' => 'OK',
                'data' => $heading           
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            if($request->boolean('include_file', false)){
                $heading = Heading::find($id);
            } else {
                $heading = Heading::with(['file'])->where('id', $id)->first();
            }  
            if(!$heading){
                return response()->json([
                    'status' => 404,
                    'message' => 'El recurso al que desea acceder no existe'        
                ], 404);
            }
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $heading           
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
     * @param  App\Http\Requests\UpdateHeadingRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHeadingRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            $heading = Heading::find($id);
            if(!$heading){
                return response()->json([
                    'status' => 404,
                    'message' => 'El recurso al que desea acceder no existe'        
                ], 404);
            }
            $heading->set($validatedData);
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $heading           
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
