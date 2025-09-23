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


class AuthenticationController extends Controller
{
    /**
     * Maneja la peticion de creacion de nuevo usuario
     * 
     */
    public function register (StoreRedactaUserRequest $request)
    {
        if(!$request->has('token')) {
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
        $user->assignRole('editor');
        $invitation->markAsUsed();
        return response()->json([
            'status' => 201,
            'message' => 'OK',
            'data' => $user
        ], 200);
    }

    /**
     * Maneja la peticion de inicio de sesión
     * 
     */
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

    /**
     * Validate sign up invitation token
     * 
     */
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
                'is_valid' => $isValid
            ]
        ], 200);
    }
    
    /**
     * Maneja la peticion de cierre de sesión
     * 
     */
    public function logout (Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }
}

