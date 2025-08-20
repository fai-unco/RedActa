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
        $adminMode = $request->query('admin_mode', false);    
        $this->authorize('viewAny', [Stamp::class, $adminMode]);
        if ($request->query('admin_mode', false)) {
            $results = Stamp::all();
        } else {
            $results = Stamp::where('redacta_user_id', '=', $request->user()->id)->get();
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
     * @param  App\Http\Requests\StoreStampRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStampRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['redacta_user_id'] = $request->user()->id;
        $stamp = Stamp::create($validatedData);
        return response()->json([
            'status' => 201,
            'message' => 'OK',
            'data' => $stamp          
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Stamp $stamp
     * @return \Illuminate\Http\Response
     */
    public function show(Stamp $stamp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Stamp $stamp
     * @return \Illuminate\Http\Response
     */
    public function edit(Stamp $stamp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UpdateStampRequest  $request
     * @param \App\Models\Stamp $stamp
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStampRequest $request, Stamp $stamp)
    {
        $this->authorize('update', $stamp);
        $validatedData = $request->validated();
        $stamp->update($validatedData);
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $stamp           
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\Stamp $stamp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Stamp $stamp)
    {
        $this->authorize('delete', $stamp);
        $stamp->delete();
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $stamp           
        ]);   
    }
}
