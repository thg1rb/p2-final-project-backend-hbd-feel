<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToProvider(Request $request)
    {
        $from = $request->query('from', '');

        session(['oauth_from' => $from]);

        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        $from = session('oauth_from', '');

        if ((!$user && $from != "svelte") || $user && $user->role != UserRole::NISIT_DEV && $from != "svelte") {
            return view('auth.register-disabled');
        } else if ((!$user && $from == "svelte") || $user && $from == "svelte" && $user->role == UserRole::NISIT_DEV) {
            return redirect("http://localhost:3000/oauth");
        }



        if ($from == 'svelte') {
            $token = $user->createToken('svelte-app')->plainTextToken;
            if (!$user->email_verified_at) {
                return redirect("http://localhost:3000/oauth?token={$token}&activate=true");
            }
            return redirect("http://localhost:3000/oauth?token={$token}");
        }

        Auth::login($user);
        return redirect()->intended('/dashboard');
    }
}
