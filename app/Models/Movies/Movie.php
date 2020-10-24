<?php

namespace App\Models\Movies;

use App\Models\Watched\Watched;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Movie extends Model
{
    use HasFactory;

    protected $appends = [
        //
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        //
    ];

    public function isDeletable() : bool
    {
        return true;
    }

    protected function getBaseRouteAttribute() : string
    {
        return '';
    }

    public function watched() : MorphMany
    {
        return $this->morphMany(Watched::class, 'watchable');
    }
}