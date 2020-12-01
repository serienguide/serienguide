<?php

namespace App\Models\Genres;

use App\Models\Movies\Movie;
use App\Traits\HasSlug;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory, HasSlug;

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
        'name',
        'id',
    ];

    public $incrementing = false;

    public function isDeletable() : bool
    {
        return true;
    }
    public function getBadgeAttribute() : string
    {
        return '<span class="inline-flex items-center mt-1 px-3 py-0.5 rounded-full text-xs font-bold bg-gray-700 text-white">' . $this->name . '</span>';
    }

    public function movies()
    {
        return $this->morphedByMany(Movie::class, 'medium', 'genre_medium');
    }
}