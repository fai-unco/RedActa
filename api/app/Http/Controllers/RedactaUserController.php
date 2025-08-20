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
    public function index()
    {
        $users = RedactaUser::all();
        return response()->json([
            'status' => 200,
            'description' => 'OK',
            'data' => $users       
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
        if (isset($validatedData['role'])) {
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
            'message' => 'El usuario ha sido eliminado correctamente'
        ]);
    }
}
