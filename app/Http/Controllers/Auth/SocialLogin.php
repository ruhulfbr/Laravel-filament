<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialLogin extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            Log::error(ucfirst($provider) . " Social Login Failed. Message: " . $e->getMessage());
            return redirect()->route('login')->with('error', 'Failed to authenticate with ' . ucfirst($provider));
        }

        // Check if the social account already exists
        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->with(['user' => function ($query) {
                $query->withTrashed();
            }])
            ->first();

        if ($socialAccount) {
            $user = $socialAccount->user;
            if ($user && $user->trashed()) {
                $user->restore();

                $user->name = $socialUser->getName();
                $user->password = NULL;
                $user->save();
            }

            Auth::login($user);
        } else {
            $existingUser = User::where('email', $socialUser->getEmail())->first();

            if ($existingUser) {
                $socialAccount = new SocialAccount([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'provider_token' => $socialUser->token,
                ]);
                $existingUser->socialAccounts()->save($socialAccount);

                Auth::login($existingUser);
            } else {
                $newUser = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'email_verified_at' => date('Y-m-d H:i:s')
                ]);

                $socialAccount = new SocialAccount([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'provider_token' => $socialUser->token,
                ]);
                $newUser->socialAccounts()->save($socialAccount);

                Auth::login($newUser);
            }
        }

        return redirect()->route('dashboard');
    }
}
