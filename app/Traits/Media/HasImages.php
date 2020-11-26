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

    public function createImageFromTmdb(string $type, string $path) : Image
    {
        return Image::createFromTmdb([
            'type' => $type,
            'path' => $path,
            'medium_type' => self::class,
            'medium_id' => $this->id,
        ]);
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