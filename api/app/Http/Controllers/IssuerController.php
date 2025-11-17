<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issuer;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreIssuerRequest;
use App\Http\Requests\UpdateIssuerRequest;


class IssuerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $request = request();
        $adminMode = $request->boolean('admin_mode', false);
        $includeInactive = $request->boolean('include_inactive', false);
        // Pass admin_mode to the policy
        $this->authorize('viewAny', [Issuer::class, $adminMode]);
        if ($adminMode && $includeInactive) {
            // return only soft-deleted (inactive) issuers
            $issuers = Issuer::onlyTrashed();
        } else {
            // return only not-deleted issuers
            $issuers = Issuer::query();
        }
        return response()->json([
            'status' => 200,
            'description' => 'OK',
            'data' => $issuers->get()    
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
     * @param  App\Http\Requests\StoreIssuerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIssuerRequest $request)
    {
        $this->authorize('create', Issuer::class);
        $validatedData = $this->validateRequest($request);
        $issuer = new Issuer(); 
        $issuer->set($validatedData);
        return response()->json([
            'status' => 201,
            'message' => 'OK',
            'data' => $issuer           
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Issuer $issuer
     * @return \Illuminate\Http\Response
     */
    public function show(Issuer $issuer)
    {
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $issuer           
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Issuer $issuer
     * @return \Illuminate\Http\Response
     */
    public function edit(Issuer $issuer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UpdateIssuerRequest  $request
     * @param \App\Models\Issuer $issuer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateIssuerRequest $request, Issuer $issuer)
    {
        $this->authorize('update', $issuer);
        $validatedData = $request->validated();
        $issuer->set($validatedData);
        return response()->json([
            'status' => 201,
            'message' => 'OK',
            'data' => $issuer           
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Issuer $issuer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Issuer $issuer)
    {
        $this->authorize('delete', $issuer);
        $issuer->delete();
        return response()->json([
            'status' => 200,
            'message' => 'OK'           
        ]);
    }
}