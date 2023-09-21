<?php

namespace App\Http\Controllers;

use App\Models\RedactaUser as User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    /**
     * Maneja la peticion de creacion de nuevo usuario
     * 
     */
    public function register (Request $request)
    {
        $validatedData = $this->validateRequest($request);
        try {
            $user = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password)       
            ]);
            return response()->json([
                'status' => 201,
                'message' => 'OK',
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Maneja la peticion de inicio de sesión
     * 
     */
    public function login (Request $request)
    {
        try {
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
                    'username' => $user->name.' '.$user->last_name
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    
    /**
     * Maneja la peticion de cierre de sesión
     * 
     */
    public function logout (Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }


    private function validateRequest($request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',        
            'last_name' => 'required|string|max:255',        
            'email' => 'required|email|unique:redacta_users',              
            'password' => 'required|confirmed|min:8',  
            //'roleId' => 'sometimes|numeric'
        ], [
            'required' => 'El campo :attribute es requerido',
            'numeric' => 'El campo :attribute debe ser un número',
            'string' => 'El campo :attribute debe ser un string',
            'password.confirmed' => 'La contraseña ingresada y su confirmación no coinciden',
            'password.min' => 'La contraseña ingresada debe tener una longitud no menor a 8 caracteres',
            'email.unique' => 'El email ingresado ya existe. Intente con otro distinto',
            'max'=> 'El valor del campo :attribute debe tener una longitud inferior a 255 caracteres'
        ], [
            'email' => '"Email"',
            'name' => '"Nombre"',
            'last_name' => '"Apellido"',
            'password' => '"Contraseña"',
            //'roleId' => '"Rol"',
        ])->stopOnFirstFailure(true);
        return $validator->validate();
    }
}

