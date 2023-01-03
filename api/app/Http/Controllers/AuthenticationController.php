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
        try {
            // Se valida los datos que viene en $request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',        
                'last_name' => 'required|string|max:255',        
                'email' => 'required|email|unique:users|max:255',              
                'password' => 'required|confirmed|min:8',    
            ]); 
            
            // Si ocurre algun error en la validacion, retorna informacion sobre el error  
            if ($validator->fails()) {       
                $errors = $validator->errors();        
                return response()->json(['errors' => $errors], 400);    
            }    
            
           // Si la validacion es exitosa, crea el usuario y el token de acceso personal para el usuario. Retorna el token
            if ($validator->passes()) {
                $user = User::create([
                    'name' => $request->name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)       
                ]);
                return response()->json([
                    'message' => 'Registration OK',
                ], 200);
            }
            } catch (\Throwable $th) {
                return response()->json(['message' => '$th->getMessage()'], 500);
            }
        }

        /**
         * Maneja la peticion de inicio de sesión
         * 
         */
        public function login (Request $request)
        {
          try {
            //Valida las credenciales del usuario
            if (!Auth::attempt($request->only('email', 'password'))){
                return response()->json([
                    'message' => 'Email y/o contraseña inválidos'
                ], 401);
            }
      
            //Busca al usuario en la base de datos
            $user = User::where('email', $request['email'])->firstOrFail();

            //Actualiza fecha y hora de último acceso
            $user->last_access = now();
            $user->save();
      
            //Genera un nuevo token para el usuario
            $token = $user->createToken('auth_token')->plainTextToken;
      
            //Retorna un JSON con el token generado y el tipo de token
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer'
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
}

