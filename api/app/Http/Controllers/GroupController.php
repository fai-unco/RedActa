<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\Group;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $viewAll = $request->query('view_all', false);
        $this->authorize('viewAny', Group::class);
        if ($viewAll) {
            $groups = Group::with('redactaUsers')->get();
        } else {
            $groups = auth()->user()->groups->load('redactaUsers');
        }
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $groups
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
     * @param  \App\Http\Requests\StoreGroupRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGroupRequest $request)
    {
        $this->authorize('create', Group::class);
        $data = $request->validated();
        $group = Group::create($data);
        return response()->json([
            'status' => 201,
            'message' => 'OK',
            'data' => $group
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        try {
            $this->authorize('view', $group);
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $group
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
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGroupRequest  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        $this->authorize('update', $group);
        $data = $request->validated();
        $group->update($data);
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $group
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $this->authorize('delete', $group);
        $group->delete();
        return response()->json([
            'status' => 200,
            'message' => 'OK'
        ], 200);
       
    }
}