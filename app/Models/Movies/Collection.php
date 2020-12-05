<?php

namespace App\Models\Movies;

use App\Models\Movies\Movie;
use App\Models\Watched\Watched;
use App\Traits\HasSlug;
use App\Traits\Media\HasCard;
use App\Traits\Media\HasImages;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Collection extends Model
{
    use HasFactory,
        HasCard,
        HasImages,
        HasModelPath,
        HasSlug;

    const ROUTE_NAME = 'collections';
    const VIEW_PATH = 'collections';

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

    public function setSlug(bool $force = false) : void
    {
        if (! $this->isDirty('name') && $force === false) {
            return;
        }

        if (empty($this->name)) {
            $this->attributes['slug'] = Str::uuid();
            return;
        }

        $slug = Str::slug($this->name, '-', 'de');
        if (self::where('id', '!=', $this->id)->slug($slug)->exists()) {
            $slug .= '-' . ((self::where('name', $this->name)->count()) + 1);
        }

        $this->attributes['slug'] = $slug;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

    // public function getRouteParameterAttribute() : array
    // {
    //     return [
    //         Str::singular($this->base_route) => $this->slug
    //     ];
    // }

    public function getRuntimeAttribute() : int
    {
        return $this->movies->sum('runtime');
    }

    public function getBudgetAttribute() : int
    {
        return $this->movies->sum('budget');
    }

    public function getRevenueAttribute() : int
    {
        return $this->movies->sum('revenue');
    }

    public function getLastWatchedAttribute()
    {
        if (Arr::has($this->attributes, 'last_watched')) {
            return Arr::get($this->attributes, 'last_watched');
        }

        if (! auth()->check()) {
            return null;
        }

        return Watched::with('watchable')
            ->where('user_id', auth()->user()->id)
            ->where('watchable_type', Movie::class)
            ->whereIn('watchable_id', $this->movies->pluck('id'))
            ->latest()
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function getProgressAttribute() : array
    {
        if ($this->progress) {
            return $this->progress;
        }

        $watchable_count = $this->movies->count();
        $watchable_runtime = $this->movies->sum('runtime');
        $unwatched_runtime = $watchable_runtime;
        if (auth()->check()) {
            $watched_models = $this->movies->filter(function ($movie) {
                return $movie->watched_count > 0;
            });
            $unwatched_models = $this->movies->filter(function ($movie) {
                return $movie->watched_count == 0;
            });
            $watched_count = $watched_models->count();
            $unwatched_count = $unwatched_models->count();
            $unwatched_runtime = $unwatched_models->sum('runtime');
            $watched_runtime = $watched_models->sum('runtime');
            return $this->progress = [
                'watched_count' => $watched_count,
                'percent' => ($watchable_count == 0 ? 0 : min(100, round($watched_count / $watchable_count * 100, 0))),
                'watchable_count' => $watchable_count,
                'watchable_runtime' => $watchable_runtime,
                'unwatched_count' => $unwatched_count,
                'watched_runtime' => $watched_runtime,
                'unwatched_runtime' => $unwatched_runtime,
                'labels' => [
                    'singular' => 'Film',
                    'plural' => 'Filme',
                ],
            ];
        }

        return $this->progress = [
            'watched_count' => 0,
            'percent' => 0,
            'watchable_count' => $watchable_count,
            'watchable_runtime' => $watchable_runtime,
            'watched_runtime' => 0,
            'unwatched_count' => $watchable_count,
            'unwatched_runtime' => $unwatched_runtime,
            'labels' => [
                'singular' => 'Film',
                'plural' => 'Filme',
            ],
        ];
    }

    public function movies() : HasMany
    {
        return $this->hasMany(Movie::class, 'collection_id');
    }
}