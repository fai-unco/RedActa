<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSignupInvitationRequest;
use App\Http\Requests\UpdateSignupInvitationRequest;
use App\Models\SignupInvitation;

class SignupInvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreSignupInvitationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSignupInvitationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SignupInvitation  $signupInvitation
     * @return \Illuminate\Http\Response
     */
    public function show(SignupInvitation $signupInvitation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SignupInvitation  $signupInvitation
     * @return \Illuminate\Http\Response
     */
    public function edit(SignupInvitation $signupInvitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSignupInvitationRequest  $request
     * @param  \App\Models\SignupInvitation  $signupInvitation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSignupInvitationRequest $request, SignupInvitation $signupInvitation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SignupInvitation  $signupInvitation
     * @return \Illuminate\Http\Response
     */
    public function destroy(SignupInvitation $signupInvitation)
    {
        //
    }
}
