<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupMembershipRequest;
use App\Http\Requests\UpdateGroupMembershipRequest;
use App\Models\GroupMembership;
use App\Models\Group;
use App\Models\RedactaUser;

class GroupMembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groupMemberships = auth()->user()->groupMemberships()->with(['group', 'redactaUser'])->get();
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $groupMemberships
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
     * @param  \App\Http\Requests\StoreGroupMembershipRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGroupMembershipRequest $request)
    {
        try {
            $data = $request->validated();
            $group = Group::find($data['group_id']);
            $redactaUser = RedactaUser::find($data['redacta_user_id']);
            if ($group->redactaUsers->contains($redactaUser)) {
                return response()->json([
                    'status' => 422,
                    'message' => 'El usuario ya es miembro del grupo'
                ], 422);
            }
            $groupMembership = GroupMembership::create($data);
            return response()->json([
                'status' => 201,
                'message' => 'OK',
                'data' => $groupMembership
            ], 201);
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
     * @param  \App\Models\GroupMembership  $groupMembership
     * @return \Illuminate\Http\Response
     */
    public function show(GroupMembership $groupMembership)
    {
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $groupMembership
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GroupMembership  $groupMembership
     * @return \Illuminate\Http\Response
     */
    public function edit(GroupMembership $groupMembership)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGroupMembershipRequest  $request
     * @param  \App\Models\GroupMembership  $groupMembership
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGroupMembershipRequest $request, GroupMembership $groupMembership)
    {
        try {
            $data = $request->validated();
            $group = Group::find($data['group_id']);
            $redactaUser = RedactaUser::find($data['redacta_user_id']);
            if ($group->redactaUsers->contains($redactaUser)) {
                return response()->json([
                    'status' => 422,
                    'message' => 'El usuario ya es miembro del grupo'
                ], 422);
            }
            $groupMembership->update($data);
            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => $groupMembership
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
     * @param  \App\Models\GroupMembership  $groupMembership
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupMembership $groupMembership)
    {
        try {
            $groupMembership->delete();
            return response()->json([
                'status' => 200,
                'message' => 'OK'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en el servidor. Reintente la operación'
            ], 500);
        }
    }
}
