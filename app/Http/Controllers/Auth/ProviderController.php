<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

        $user = User::firstWhere([
            'email' => $provider_user->getEmail(),
            'provider' => null,
            'provider_id' => null,
        ]);

        if (is_null($user)) {
            $user = User::firstOrCreate([
                'provider' => $provider,
                'provider_id' => $provider_user->getId(),
            ], [
                'email' => $provider_user->getEmail(),
                'name' => $provider_user->getName(),
            ]);
        }
        else {
            $user->update([
                'provider' => $provider,
                'provider_id' => $provider_user->getId(),
            ]);
        }

        auth()->login($user, true);

        return redirect(route('dashboard'));
    }
}
