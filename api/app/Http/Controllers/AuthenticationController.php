<?php

namespace App\Http\Controllers;

use App\Models\RedactaUser as User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreRedactaUserRequest;
use App\Models\SignupInvitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\PasswordReset;
use App\Mail\PasswordResetMail;
use Carbon\Carbon;


class AuthenticationController extends Controller
{
    public function register (StoreRedactaUserRequest $request)
    {
        if (!$request->has('token')) {
            return response()->json([
                'status' => 400,
                'message' => 'Token is required'
            ], 400);
        }
        $invitation = SignupInvitation::where('token', $request->get('token'))->first();
        if (!$invitation || !$invitation->isValid()) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid token'
            ], 400);
        }
        $validatedData = $request->validated();
        $user = User::create([
            'name' => $validatedData['name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
        if ($invitation->role_id) {
            $role = \Spatie\Permission\Models\Role::find($invitation->role_id);
            $user->assignRole($role->name);
        } 
        if (!$user->roles->isNotEmpty()) {
            $user->assignRole('editor');
        }
        $invitation->markAsUsed();
        return response()->json([
            'status' => 201,
            'message' => 'OK',
            'data' => $user
        ], 200);
    }

    public function login (Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'status' => 401,
                'message' => 'Email y/o contraseña inválidos'
            ], 401);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        $user->last_access = now();
        $user->save();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'username' => $user->name.' '.$user->last_name,
                'redacta_user_id' => $user->id,
                'role' => $user->roles->pluck('name')->first()
            ]
        ], 200);
    }

    public function validateSignupInvitation(Request $request) {
        if (!$request->has('token')) {
            return response()->json([
                'status' => 400,
                'message' => 'Token is required'
            ], 400);
        }
        $invitation = SignupInvitation::where('token', $request->get('token'))->first();
        $isValid = $invitation && $invitation->isValid();
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => [
                'is_valid' => $isValid,
                'invitation' => $invitation
            ]
        ], 200);
    }
    
    public function logout (Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['status' => 400, 'message' => 'El mail ingresado no está asociado a ninguna cuenta'], 400);
        }
        $token = Str::random(64);
        $passwordReset = PasswordReset::create([
            'email' => $email,
            'token' => $token, 
            'created_at' => now()
        ]);
        $frontendBase = env('APP_URL', config('app.app_url', ''));
        $link = $frontendBase . '/reset-password?token=' . $token;
        Mail::to($email)->send(new PasswordResetMail($user, $link, $passwordReset->created_at->addMinutes(60)->format('d/m/Y H:i')));
        return response()->json(['status' => '200', 'message' => 'OK']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $token = $request->input('token');
        $passwordReset = PasswordReset::where('token', $token)
            ->where('used', false)
            ->first();
        if (!$passwordReset) {
            return response()->json(['status' => 400, 'message' => 'El link es inválido'], 400);
        }
        $created = Carbon::parse($passwordReset->created_at);
        if ($created->addMinutes(60)->isPast()) {
            return response()->json(['status' => 400, 'message' => 'El link ha expirado'], 400);
        }
        $user = User::where('email', $passwordReset->email)->first();
        $user->password = Hash::make($request->input('password'));
        $user->save();
        $passwordReset->used = true;
        $passwordReset->save();
        return response()->json(['status' => 200, 'message' => 'OK']);
    }
}

