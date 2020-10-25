<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasSlug
{
    public static function bootHasSlug()
    {
        static::creating(function ($model) {
            $model->setSlug();
        });

        static::updating(function ($model) {
            $model->setSlug();
        });
    }

    public function setSlug() : void
    {
        $this->attributes['slug'] = '';
    }

    public function scopeSlug(Builder $query, $value) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->where($this->getTable() . '.slug', $value);
    }
}