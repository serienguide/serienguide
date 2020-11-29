<?php

namespace App\Traits\Media;

use Illuminate\Database\Eloquent\Builder;
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

    public function getClassNameAttribute() : string
    {
        return strtolower(class_basename($this));
    }

    public function scopeForCard(Builder $query) : Builder
    {
        return $query;
    }
}