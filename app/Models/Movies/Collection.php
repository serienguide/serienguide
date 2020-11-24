<?php

namespace App\Models\Movies;

use App\Traits\HasSlug;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory, HasModelPath, HasSlug;

    const ROUTE_NAME = '';
    const VIEW_PATH = '';

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
        'id',
        'name',
        'poster_path',
        'backdrop_path',
    ];

    protected $table = 'movie_collection';

    public function isDeletable() : bool
    {
        return true;
    }
}