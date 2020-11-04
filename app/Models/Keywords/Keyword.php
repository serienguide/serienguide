<?php

namespace App\Models\Keywords;

use App\Traits\HasSlug;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory, HasSlug;

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
        //
    ];

    public function isDeletable() : bool
    {
        return true;
    }

    public function movies()
    {
        return $this->morphedByMany(Movie::class, 'medium', 'keyword_medium');
    }
}