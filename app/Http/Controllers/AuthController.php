<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3,max:100',
            'email' => 'required|string|email|min:10|max:100|unique:users',
            'password' => 'required|string|min:6|max:100|confirmed',
            'role_id' => 'nullable|exists:roles,id', // Hacemos role_id opcional
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }

        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'role_id' => $request->get('role_id', 2), // 2 sería el ID del rol 'user'
        ]);

        return response()->json(['message' => 'User created successfully'], 201);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' =>'required|string|email|min:10|max:100',
            'password' =>'required|string|min:6|max:100',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        $credentials = $request->only('email', 'password');
        
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
            return response()->json(['token' => $token], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token', $e], 500);
        }
    }

    public function getUser(){
        $user = Auth::user();
        return response()->json(['user' => $user], 200);
    }

    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'User logged out successfully'], 200);
    }
}
