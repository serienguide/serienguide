<?php

namespace App\Traits\Media;

use App\Models\Images\Image;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasImages
{
    public static function bootHasImages()
    {
        static::creating(function ($model) {
            //
        });
    }

    public function initializeHasImages()
    {
        //
    }

    public function images() : MorphMany
    {
        return $this->morphMany(Image::class, 'medium');
    }

    public function posters() : MorphMany
    {
        return $this->images()->where('type', 'poster');
    }

    public function backdrops() : MorphMany
    {
        return $this->images()->where('type', 'backdrop');
    }
}