<?php

namespace App\Models\People;

use App\Traits\HasSlug;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory, HasModelPath, HasSlug;

    const ROUTE_NAME = 'people';
    const VIEW_PATH = 'people';

    protected $appends = [
        //
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'birthday_at',
        'deathday_at',
    ];

    protected $fillable = [
        'name',
        'birthday_at',
        'deathday_at',
        'known_for_department',
        'gender',
        'biography',
        'place_of_birth',
        'poster_path',
        'backdrop_path',
        'homepage',
    ];

    public function isDeletable() : bool
    {
        return true;
    }
}