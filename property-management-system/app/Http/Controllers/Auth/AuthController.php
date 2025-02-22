<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   public function register(Request $request)
   {
    //validate the credentilas
    $validated=$request->validate(
        [
            'name'=>'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users',
            'password'=>'required|min:8',
        ]
    );

    $user = User::create([
      'name'=>$validated['name'],
      'email'=>$validated['email'],
      'password'=>bcrypt($validated['password'])
    ]);

    return response()->json(['token'=>$user->createToken('authToken')->plainTextToken],201);
   }

   public function login(Request $request)
   {
    if(!Auth::attempt($request->only('email','password')))
    {
       return response()->json(['message'=>'Unauthorized user'],401);
    }
    $user = Auth::user();
    return response()->json(['token' => $user->createToken('authToken')->plainTextToken],201);
   }


   public function logout(Request $request)
   {
    $request->user()->tokens()->delete();
    return response()->json(['message'=>'Logged out successfully'],200);
   }
}
