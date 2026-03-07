<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
                'user' => $this->userData($user),
            ]);

        } catch (ValidationException $exception) {
            return response()->json(['message' => 'Invalid Credentials', 'reason' => $exception], 401);
        }

    }

    private function userData($user) {
        return [
            'name' => $user->firstName . " " . $user->lastName,
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'student_id' => $user->student_id,
            'force_password_change' => !$user->email_verified_at
        ];
    }

    public function me(Request $request) {
        $user = $request->user();
        return response()->json([
            'user' => $this->userData($user),

        ]);
    }

    public function revoke(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Token revoked'
        ]);
    }
    public function changePassword(Request $request) {
        $user = $request->user();

        $rules = [
            'password' => ['required', \Illuminate\Validation\Rules\Password::defaults(), 'confirmed'],
        ];

        // Only require old password if NOT first login
        if ($user->email_verified_at) {
            $rules['current_password'] = ['required', 'current_password'];
        }

        $validated = $request->validate($rules);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $user->markEmailAsVerified();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }

    public function changeUserDetails(ProfileUpdateRequest $request) {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => $this->userData($request->user())
        ]);
    }
}
