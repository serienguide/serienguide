<?php

namespace App\Traits\Media;

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

    public function getCardImagePathAttribute() : string
    {
        return Storage::disk('s3')->url($this->poster_path ? 'w680' . $this->poster_path : 'no/680x1000.png');
    }
}