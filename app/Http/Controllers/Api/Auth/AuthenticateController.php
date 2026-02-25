<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthenticateController extends Controller
{
    function login(LoginRequest $request) {
        try {
            $request->authenticate();
            $user = User::query()->where('email', $request->credential)->first();
            if (!$user) {
                $user = User::query()->where('username', $request->credential)->first();
                if (!$user) {
                    throw ValidationException::withMessages(["credential" => "User not found"]);
                }
            }
            $token = $user->createToken('auth_token', [$user->role])->plainTextToken;
//
            return response()->json([
                'token' => $token,
//                    'message' => 'User logged in successfully',
                'user' => [
                    'name' => $user->firstName." ".$user->lastName,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ]);

        } catch (ValidationException $exception) {
            return response()->json(['message' => 'Invalid Credentials', 'reason' => $exception], 401);
        }

    }

    public function revoke(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Token revoked'
        ]);
    }
}
