<?php

namespace App\Traits;

use App\Models\Lists\Item;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasManyLists
{
    public static function bootHasManyLists()
    {
        static::creating(function ($model) {
            //
        });
    }

    public function initializeHasManyLists()
    {
        //
    }

    public function list_items() : MorphMany
    {
        return $this->morphMany(Item::class, 'medium');
    }
}