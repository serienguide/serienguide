<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\OauthProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class ProviderController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(string $provider)
    {
        $provider_user = Socialite::driver($provider)->user();
        $provider_id = $provider_user->getId() ?? $provider_user->getNickname();

        if (auth()->check()) {
            $user = auth()->user();
        }
        else {
            $user = User::whereHas('oauth_providers', function ($query) use ($provider, $provider_id) {
                $query->where('provider_type', $provider)
                    ->where('provider_id', $provider_id);
            })->first();

            if (is_null($user) && $provider_user->getEmail()) {
                $user = User::where('email', $provider_user->getEmail())->first();
            }

            if (is_null($user)) {
                $user = User::create([
                    'email' => $provider_user->getEmail(),
                    'name' => $provider_user->getName(),
                ]);
            }
        }

        $user->oauth_providers()->updateOrCreate([
            'provider_type' => $provider,
            'provider_id' => $provider_id,
        ], [
            'token' => $provider_user->token,
            'token_secret' => null, // only available on OAuth1: $provider_user->tokenSecret,
            'refresh_token' => $provider_user->refreshToken, // only available on OAuth2
            'expires_in' => $provider_user->expiresIn, // only available on OAuth2
            'expires_at' => ($provider_user->expiresIn ? now()->addSeconds($provider_user->expiresIn) : null), // only available on OAuth2
        ]);

        if (auth()->check()) {
            return redirect(OauthProvider::indexPath());
        }

        auth()->login($user, true);

        return redirect(route('dashboard'));
    }
}
