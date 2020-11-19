<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

trait BelongsToMedium
{
    public function medium() : MorphTo
    {
        return $this->morphTo('medium');
    }

    public function scopeMedium(Builder $query, $type, $id) : Builder
    {
        if (is_null($id)) {
            return $query;
        }

        return $query->where($this->getTable() . '.medium_type', $type)
            ->where($this->getTable() . '.medium_id', $id);
    }
}