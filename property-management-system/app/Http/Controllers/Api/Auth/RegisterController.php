<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request): JsonResponse
    {
        try {
            // Validate request data (min 8 with 1 letter, 1 number, 1 special char)
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => [
                    'required',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*#?&]/',
                ]
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            //based on the requirement , you can return the user data or a token for security
            return ApiResponse::success('User registered successfully.', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                // 'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 201);

        } catch (\Throwable $th) {
            Log::error('Error creating user: ' . $th->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to create user due to server error'
            ], 500);
        }
    }
}
