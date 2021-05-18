<?php

namespace App\Traits\Media;

use App\Models\User;
use App\Models\Watched\Watched;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
        $this->append([
            'watched_path',
            'watched_event_name',
        ]);
    }

    public function getWatchedEventNameAttribute() : string
    {
        return $this->class_name . '_' . $this->id . '_watched';
    }

    public function getWatchedPathAttribute() : string {
        return route('media.watched.store', [
            'media_type' => $this->media_type,
            'model' => $this->id,
        ]);
    }

    public function watchedBy(User $user, array $attributes = []) : Watched
    {
        $watchlist = $user->lists()->where('type', 'watchlist')->first();
        $watchlist->items()
            ->where('medium_type', self::class)
            ->where('medium_id', $this->id)
            ->delete();

        return $this->watched()->create([
            'user_id' => $user->id,
            'watched_at' => Arr::get($attributes, 'watched_at', now()),
        ]);
    }

    public function watched() : MorphMany
    {
        return $this->morphMany(Watched::class, 'watchable');
    }

    public function watchedByUser(int $user_id)
    {
        return $this->watched()->where('user_id', $user_id);
    }
}