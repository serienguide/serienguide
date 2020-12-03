<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        \App\Models\Lists\Listing::class => \App\Policies\Lists\ListingPolicy::class,
        \App\Models\Movies\Movie::class => \App\Policies\Movies\MoviePolicy::class,
        \App\Models\Auth\OauthProvider::class => \App\Policies\Auth\OauthProviderPolicy::class,
        \App\Models\Ratings\Rating::class => \App\Policies\Ratings\RatingPolicy::class,
        \App\Models\Watched\Watched::class => \App\Policies\Watched\WatchedPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
