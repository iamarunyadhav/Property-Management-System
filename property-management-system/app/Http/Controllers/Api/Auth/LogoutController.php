<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    /**
     * Log out the authenticated user.
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Logged out successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to log user out'], 500);
        }
    }
}
