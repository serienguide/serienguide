<?php

namespace App\Models\People;

use App\Traits\HasSlug;
use App\Traits\Media\HasImages;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory,
        HasImages,
        HasModelPath,
        HasSlug;

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
        'id',
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
        'vote_count',
        'vote_average',
        'tmdb_vote_count',
        'tmdb_vote_average',
    ];

    public $incrementing = false;

    public function isDeletable() : bool
    {
        return true;
    }

    public function getPosterPathAttribute() : string
    {
        return $this->profile_path;
    }
}