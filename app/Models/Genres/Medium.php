<?php

namespace App\Models\Genres;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Medium extends MorphPivot
{
    protected $table = 'genre_medium';
}
