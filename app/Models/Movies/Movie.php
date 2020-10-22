<?php

namespace App\Models\Movies;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}