<?php

namespace App\Traits\Media;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait MorphsToManyGenres
{
    public static function bootMorphsToManyGenres()
    {
        static::creating(function ($model) {
            //
        });
    }

    public function initializeMorphsToManyGenres()
    {
        //
    }

    public function genres() : MorphToMany
    {
        return $this->morphToMany(Genre::class, 'medium', 'genre_medium');
    }

    // scopeHasGenres
    // scopeHasNotGenres
}