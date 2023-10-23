<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Heading;
use Illuminate\Support\Facades\Validator;


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
            - year (number): response will contain only headings of the specified year
        */  
        try {       
            $searchParameters = [];
            if($request->boolean('include_file', false)){
                $headings = Heading::with(['file']);
            } else {
                $headings = Heading::all();
            }
            if($request->has('issuer_id')){
                array_push($searchParameters, ['issuer_id', '=', $request->query('issuer_id')]);
            }
            if($request->has('year')){
                array_push($searchParameters, ['year', '=', $request->query('year')]);
            }
            $headings = $headings->where($searchParameters)->first();
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $headings           
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateRequest($request);
        try {
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $this->validateRequest($request);
        try {
            $heading = heading::find($id);
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

    private function validateRequest($request){
        $validator = Validator::make($request->all(), [
            'year' => 'required|numeric',
            'issuer_id' => 'required|numeric',
            'file_id' => 'required|numeric|exists:files,id'
        ], [
            'required' => 'El campo :attribute es requerido',
            'numeric' => 'El campo :attribute debe ser un número',
        ], [
            'year' => '"Año"',
            'file_id' => '"Seleccionar archivo"'
        ])->stopOnFirstFailure(true);
        $validator->validate();
        return $validator->validated();
    }


}
