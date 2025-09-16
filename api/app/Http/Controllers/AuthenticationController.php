<?php

namespace App\Http\Controllers;

use App\Models\RedactaUser as User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreRedactaUserRequest;

class AuthenticationController extends Controller
{
    /**
     * Maneja la peticion de creacion de nuevo usuario
     * 
     */
    public function register (StoreRedactaUserRequest $request)
    {
        $this->authorize('create', User::class);
        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole($request->role);
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
     * Maneja la peticion de cierre de sesión
     * 
     */
    public function logout (Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }
}

