<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToUser
{
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeUser(Builder $query, $value) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->where($this->getTable() . '.user_id', $value);
    }
}