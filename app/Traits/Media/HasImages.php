<?php

namespace App\Traits\Media;

use App\Models\Images\Image;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

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
        $this->append([
            // 'poster_url'
        ]);
    }

    public function createImageFromTmdb(string $type, $path)
    {
        if (empty($path)) {
            return;
        }

        $image = Image::createFromTmdb([
            'type' => $type,
            'path' => $path,
            'medium_type' => self::class,
            'medium_id' => $this->id,
        ]);

        $this->deleteUnusedImages($type);

        return $image;
    }

    public function deleteUnusedImages(string $type): int
    {
        $count = 0;
        $path_name = $type . '_path';
        $images = $this->images()
            ->where('path', '!=', $this->$path_name)
            ->where('type', $type)
            ->get();

        foreach ($images as $image) {
            $image->delete();
            $count++;
        }

        return $count;
    }

    public function getPosterUrlOriginalAttribute() : string
    {
        return Storage::disk('s3')->url($this->poster_path ? 'original' . $this->poster_path : 'no/680x1000.png');
    }

    public function getPosterUrlAttribute() : string
    {
        return Storage::disk('s3')->url($this->poster_path ? 'w680' . $this->poster_path : 'no/680x1000.png');
    }

    public function getPosterUrlSmAttribute() : string
    {
        return Storage::disk('s3')->url($this->poster_path ? 'w118' . $this->poster_path : 'no/680x1000.png');
    }

    public function getPosterUrlXsAttribute() : string
    {
        return Storage::disk('s3')->url($this->poster_path ? 'w48' . $this->poster_path : 'no/680x1000.png');
    }

    public function getBackdropUrlAttribute() : string
    {
        return Storage::disk('s3')->url($this->backdrop_path ? 'w423' . $this->backdrop_path : 'no/750x422.png');
    }

    public function getBackdropUrlXlAttribute() : string
    {
        return Storage::disk('s3')->url($this->backdrop_path ? 'w1920' . $this->backdrop_path : 'no/750x422.png');
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