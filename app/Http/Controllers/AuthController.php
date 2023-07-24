<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
   // Function to register a new user
   public function register(Request $request)
   {
       $validator = Validator::make($request->all(), [
           'name' => 'required|max:255',
           'email' => 'required|email|unique:users',
           'password' => 'required|min:6',
       ]);

       if ($validator->fails()) {
           return response()->json($validator->errors(), 422);
       }

       $user = User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => bcrypt($request->password),
       ]);

       $token = auth()->attempt($request->only('email', 'password'));
       return response()->json(['token' => $token]);
   }

   // Function to authenticate a user and return a JWT token
   public function login(Request $request)
   {
       $credentials = $request->only('email', 'password');
       if (!$token = auth()->attempt($credentials)) {
           return response()->json(['error' => 'Unauthorized'], 401);
       }
       return response()->json(['token' => $token]);
   }
}
