<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSignupInvitationRequest;
use App\Http\Requests\UpdateSignupInvitationRequest;
use App\Models\SignupInvitation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RedactaUser;

class SignupInvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', SignupInvitation::class);
        if ($request->has('token')) {
            $query = SignupInvitation::where('token', $request->query('token'));
        } else {
            $query = SignupInvitation::query();
        }
        $invitations = $query->with('redactaUser')->get();
        $invitations = $invitations->where(function ($invitation) use ($request) {
            return $request->boolean('expired', false) ?
                !$invitation->isValid(): $invitation->isValid();
        });
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $invitations->values()->toArray()
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
     * @param  \App\Http\Requests\StoreSignupInvitationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSignupInvitationRequest $request)
    {
        $this->authorize('create', SignupInvitation::class);
        $validatedData = $request->validated();
        //Check if there's a valid invitation for this email
        $existingInvitation = SignupInvitation::where('email', $validatedData['email'])->first();
        if ($existingInvitation && $existingInvitation->isValid()) {
            return response()->json([
                'status' => 400,
                'message' => 'An active invitation for this email already exists'
            ], 400);
        }
        //Check if the email is already registered
        if (RedactaUser::where('email', $validatedData['email'])->exists()) {
            return response()->json([
                'status' => 400,
                'message' => 'This email is already registered'
            ], 400);
        }
        $validatedData['redacta_user_id'] = $request->user()->id;
        $validatedData['token'] = Str::uuid()->toString();
        $signupInvitation = SignupInvitation::create($validatedData);
        //Send email
        \Mail::to($signupInvitation->email)->send(new \App\Mail\SignupInvitation($signupInvitation));
        return response()->json([
            'status' => 201,
            'message' => 'OK',
            'data' => $signupInvitation
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SignupInvitation  $signupInvitation
     * @return \Illuminate\Http\Response
     */
    public function show(SignupInvitation $signupInvitation)
    {
        $this->authorize('view', $signupInvitation);
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $signupInvitation          
        ]);
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
        $this->authorize('delete', $signupInvitation);
        $signupInvitation->delete();
        return response()->json([
            'status' => 200,
            'message' => 'OK'
        ]);
    }
}
