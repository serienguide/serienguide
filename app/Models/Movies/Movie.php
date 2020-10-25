<?php

namespace App\Models\Movies;

use App\Models\User;
use App\Models\Watched\Watched;
use App\Traits\HasSlug;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Movie extends Model
{
    use HasFactory, HasModelPath, HasSlug;

    const ROUTE_NAME = 'movies';
    const VIEW_PATH = 'movies';

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
        'homepage',
        'overview',
        'released_at',
        'runtime',
        'status',
        'tagline',
        'title',
        'year',
    ];

    public function isDeletable() : bool
    {
        return true;
    }

    public function setSlug() : void
    {
        $this->attributes['slug'] = Str::slug($this->title . '-' . $this->year, '-', 'de');
    }

    public function watchedBy(User $user, array $attributes = []) : Watched
    {
        return $this->watched()->create([
            'user_id' => $user->id,
            'watched_at' => Arr::get($attributes, 'watched_at', now()),
        ]);
    }

    public function watched() : MorphMany
    {
        return $this->morphMany(Watched::class, 'watchable');
    }
}