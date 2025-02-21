<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if ( $user ) {
            if ( Hash::check($password, $user->password) ) {
                return response()->json([
                    'user' => $user,
                    'accessToken' => $user->createToken('auth_token')->plainTextToken,
                    'success' => 1,
                ]);
            }
        }

        return response()->json([
            'message' => 'Invalid login'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
}
