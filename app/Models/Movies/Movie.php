<?php

namespace App\Models\Movies;

use App\Models\Genres\Genre;
use App\Models\Keywords\Keyword;
use App\Models\Lists\Item;
use App\Models\User;
use App\Models\Watched\Watched;
use App\Traits\HasManyLists;
use App\Traits\HasSlug;
use App\Traits\Media\MorphsToManyGenres;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Movie extends Model
{
    use HasFactory, HasManyLists, HasModelPath, HasSlug, MorphsToManyGenres;

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

    public function keywords() : MorphToMany
    {
        return $this->morphToMany(Keyword::class, 'medium', 'keyword_medium');
    }

    public function watched() : MorphMany
    {
        return $this->morphMany(Watched::class, 'watchable');
    }

    public function scopeSearch(Builder $query, $value) : Builder
    {
        if (empty($value)) {
            return $query;
        }

        return $query->where('title', 'LIKE', '%' . $value . '%');
    }
}