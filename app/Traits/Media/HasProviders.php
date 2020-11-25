<?php

namespace App\Traits\Media;

use App\Models\Providers\Provider;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasProviders
{
    public static function bootHasProviders()
    {
        static::creating(function ($model) {
            //
        });
    }

    public function initializeHasProviders()
    {
        //
    }

    public function providers() : MorphToMany
    {
        return $this->morphToMany(Provider::class, 'medium', 'provider_medium')
            ->withPivot([
                'display_priority',
                'type',
            ]);
    }

    protected function syncProvidersFromTmdb(array $tmdb_providers)
    {
        $provider_ids = [];
        foreach ($tmdb_providers as $type => $types) {
            if (! is_array($types)) {
                continue;
            }
            foreach ($types as $key => $tmdb_provider) {
                $provider = Provider::firstOrCreate([
                    'id' => $tmdb_provider['provider_id'],
                ], [
                    'name' => $tmdb_provider['provider_name'],
                    'logo_path' => $tmdb_provider['logo_path'],
                ]);
                $provider_ids[$provider->id] = [
                    'display_priority' => $tmdb_provider['display_priority'],
                    'type' => $type,
                ];
            }
        }

        $this->providers()->sync($provider_ids);
    }
}