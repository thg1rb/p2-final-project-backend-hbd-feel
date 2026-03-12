<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForceChangePasswordController extends Controller
{
    public function index(Request $request) {
        return view('auth.force-change-password', []);
    }
    public function update(Request $request) {
        $user = $request->user();

        $rules = [
            'password' => ['required', \Illuminate\Validation\Rules\Password::defaults(), 'confirmed'],
        ];


        $validated = $request->validate($rules);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $user->markEmailAsVerified();

        return redirect()->route('main');
    }
}
