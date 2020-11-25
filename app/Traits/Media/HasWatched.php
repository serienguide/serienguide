<?php

namespace App\Traits\Media;

use App\Models\User;
use App\Models\Watched\Watched;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;

trait HasWatched
{
    public static function bootHasWatched()
    {
        static::creating(function ($model) {
            //
        });
    }

    public function initializeHasWatched()
    {
        //
    }

    public function watchedBy(User $user, array $attributes = []) : Watched
    {
        return $this->watched()->create([
            'user_id' => $user->id,
            'watched_at' => Arr::get($attributes, 'watched_at', now()),
        ]);
    }

    public function watched() : MorphMany
    {
        return $this->morphMany(Watched::class, 'watchable');
    }
}