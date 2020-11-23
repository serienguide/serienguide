<?php

namespace App\Models\Providers;

use App\Models\Movies\Movie;
use App\Traits\HasSlug;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
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
        'logo_path',
    ];

    public $incrementing = false;

    public function isDeletable() : bool
    {
        return true;
    }

    public function movies()
    {
        return $this->morphedByMany(Movie::class, 'medium', 'provider_medium')
            ->withPivot([
                'display_priority',
            ]);
    }
}