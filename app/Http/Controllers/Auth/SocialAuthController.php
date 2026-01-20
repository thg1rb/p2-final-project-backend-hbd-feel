<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToProvider() {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback() {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'firstName' => $googleUser->getName(),
                'lastName' => '',
                'username' => str_replace(' ', '.', $googleUser->getName()),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(fake()->password(20)),
            ]);
        }

        $user->markEmailAsVerified();

        Auth::login($user);
        return redirect()->intended('/dashboard');
    }
}
