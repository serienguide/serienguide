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

    public function scopeCardForUser(Builder $query, $user_id) : Builder
    {
        if (is_null($user_id)) {
            return $query;
        }

        return $query->select('*', DB::raw($user_id . ' AS card_user_id'));
    }
}