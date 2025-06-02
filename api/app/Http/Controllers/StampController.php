<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stamp;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreStampRequest;
use App\Http\Requests\UpdateStampRequest;

class StampController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {    
            $results = Stamp::where('redacta_user_id', '=', $request->user()->id)->get();
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
     * @param  App\Http\Requests\StoreStampRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStampRequest $request)
    {
        try {  
            $validatedData = $request->validated();
            $validatedData['redacta_user_id'] = $request->user()->id;
            $stamp = Stamp::create($validatedData);
            return response()->json([
                'status' => 201,
                'message' => 'OK',
                'data' => $stamp          
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
        //
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
     * @param  App\Http\Requests\UpdateStampRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStampRequest $request, $id)
    {
        try { 
            $validatedData = $request->validated();
            $stamp = Stamp::find($id);
            if (!$stamp || $stamp->redacta_user_id != $request->user()->id) {
                return response()->json([
                    'status' => 404,
                    'message' => 'El recurso al que desea acceder no existe'        
                ], 404);
            }
            $stamp->update($validatedData);
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $stamp           
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try { 
            $stamp = Stamp::find($id);
            if (!$stamp || $stamp->redacta_user_id != $request->user()->id) {
                return response()->json([
                    'status' => 404,
                    'message' => 'El recurso al que desea acceder no existe'        
                ], 404);
            }
            $stamp->delete();
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $stamp           
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
        }
    }
}
