<?php

namespace App\Traits\Media;

use App\Models\Movies\Movie;
use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Show;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait HasCard
{
    protected $progress;

    public static function bootHasCard()
    {
        static::creating(function ($model) {
            //
        });
    }

    public function initializeHasCard()
    {
        //
    }

    public function isClass(string $class_name)
    {
        return (get_class($this) == $class_name);
    }

    public function getClassNameAttribute() : string
    {
        return strtolower(class_basename($this));
    }

    public function getIsEpisodeAttribute() : bool
    {
        return $this->isClass(Episode::class);
    }

    public function getIsMovieAttribute() : bool
    {
        return $this->isClass(Movie::class);
    }

    public function getIsShowAttribute() : bool
    {
        return $this->isClass(Show::class);
    }

    public function getProgressAttribute() : array
    {
        if ($this->progress) {
            return $this->progress;
        }

        $watchable_count = ($this->is_show ? $this->episodes_count : 1);
        if (auth()->check()) {
            $watched_count = $this->watchedByUser(auth()->user()->id)->distinct()->count('watchable_id');
            return $this->progress = [
                'watched_count' => $watched_count,
                'percent' => ($watchable_count == 0 ? 0 : min(100, round($watched_count / $watchable_count * 100, 0))),
                'watchable_count' => ($this->is_show ? $this->episodes_count : 1),
            ];
        }

        return $this->progress = [
            'watched_count' => 0,
            'percent' => 0,
            'watchable_count' => $watchable_count,
        ];
    }

    public function scopeCardForUser(Builder $query, $user_id) : Builder
    {
        if (is_null($user_id)) {
            return $query;
        }

        return $query->select('*', DB::raw($user_id . ' AS card_user_id'));
    }
}