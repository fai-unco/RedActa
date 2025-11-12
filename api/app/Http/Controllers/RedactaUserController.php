<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RedactaUser;
use App\Http\Requests\UpdateRedactaUserRequest;


class RedactaUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $adminMode = $request->boolean('admin_mode', false);
        $this->authorize('viewAny', [RedactaUser::class, $adminMode]);
        // If the user is an admin, include soft-deleted users
        if ($adminMode && $request->boolean('include_inactive', false)) {
            $query = RedactaUser::onlyTrashed();
        } else {
            $query = RedactaUser::query();
        }
        $users = $query->where('name', 'LIKE', $request->input('name', '%'))
            ->where('last_name', 'LIKE', $request->input('last_name', '%'))
            ->where('email', 'LIKE', $request->input('email', '%'))
            ->get();
        // Apply role filter only if the requester is an admin
        if ($adminMode) {
            if ($request->has('role')) {    
                $role = $request->input('role');
                $users = $users->filter(function ($user) use ($role) {
                    return $user->hasRole($role);
                });
            }
            // Load roles relationship for admin users
            $users->load('roles');
        }
        return response()->json([
            'status' => 200,
            'description' => 'OK',
            'data' => $users->values()->toArray()
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
     * @param \App\Models\RedactaUser $redactaUser
     * @return \Illuminate\Http\Response
     */
    public function show(RedactaUser $redactaUser)
    {
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $redactaUser          
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\RedactaUser $redactaUser
     * @return \Illuminate\Http\Response
     */
    public function edit(RedactaUser $redactaUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRedactaUserRequest  $request
     * @param  RedactaUser  $redactaUser
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRedactaUserRequest $request, RedactaUser $redactaUser)
    {
        $this->authorize('update', $redactaUser);
        $validatedData = $request->validated();
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }
        // Only admins can update roles
        if (isset($validatedData['role']) && $request->user()->hasAnyRole(['local_admin', 'super_admin'])) {
            $redactaUser->syncRoles($validatedData['role']);
        }
        $redactaUser->update($validatedData);
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $redactaUser           
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(RedactaUser $redactaUser)
    {
        $this->authorize('delete', $redactaUser);
        $redactaUser->delete();
        return response()->json([
            'status' => 200,
            'message' => 'OK'
        ]);
    }

    /**
     * Reactivate a soft-deleted user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $redactaUser = RedactaUser::withTrashed()->findOrFail($id);
        $this->authorize('restore', $redactaUser);
        $redactaUser->restore();
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $redactaUser           
        ]);
    }
}
