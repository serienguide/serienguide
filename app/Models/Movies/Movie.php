<?php

namespace App\Models\Movies;

use App\Models\Genres\Genre;
use App\Models\Images\Image;
use App\Models\Keywords\Keyword;
use App\Models\Lists\Item;
use App\Models\Lists\Listing;
use App\Models\Movies\Collection;
use App\Models\People\Credit;
use App\Models\People\Person;
use App\Models\Providers\Provider;
use App\Models\Ratings\Rating;
use App\Models\User;
use App\Models\Watched\Watched;
use App\Traits\HasManyLists;
use App\Traits\HasSlug;
use App\Traits\Media\HasCard;
use App\Traits\Media\HasCredits;
use App\Traits\Media\HasGenres;
use App\Traits\Media\HasImages;
use App\Traits\Media\HasKeywords;
use App\Traits\Media\HasProviders;
use App\Traits\Media\HasRatings;
use App\Traits\Media\HasWatched;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Movie extends Model
{
    use HasCard,
        HasCredits,
        HasFactory,
        HasGenres,
        HasImages,
        HasKeywords,
        HasManyLists,
        HasModelPath,
        HasProviders,
        HasRatings,
        HasSlug,
        HasWatched;

    const ROUTE_NAME = 'movies';
    const VIEW_PATH = 'movies';

    protected $appends = [
        //
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'released_at',
    ];

    protected $fillable = [
        'backdrop_path',
        'budget',
        'collection_id',
        'homepage',
        'name',
        'name_en',
        'overview',
        'poster_path',
        'released_at',
        'revenue',
        'runtime',
        'status',
        'tagline',
        'year',
        'imdb_id',
        'tmdb_id',
        'facebook',
        'instagram',
        'twitter',
        'vote_count',
        'vote_average',
        'tmdb_vote_count',
        'tmdb_vote_average',
    ];

    public static function createOrUpdateFromTmdb(int $tmdb_id) : self
    {
        $attributes = \App\Apis\Tmdb\Movies\Movie::find($tmdb_id);
        $model = self::updateOrCreate([
            'tmdb_id' => $attributes['id'],
        ], $attributes);

        $model->syncFromTmdb($attributes);

        return $model;
    }

    public function updateFromTmdb()
    {
        $attributes = \App\Apis\Tmdb\Movies\Movie::find($this->tmdb_id);
        if (empty($attributes)) {
            return;
        }
        $this->update($attributes);
        $this->syncFromTmdb($attributes);
    }

    protected function syncFromTmdb(array $attributes)
    {
        $this->syncCollectionFromTmdb($attributes['belongs_to_collection']);
        $this->syncGenresFromTmdb($attributes['genres']);
        $this->syncKeywordsFromTmdb($attributes['keywords']);
        $this->syncProvidersFromTmdb($attributes['providers']);
        $this->createImageFromTmdb('poster', $attributes['poster_path']);
        $this->createImageFromTmdb('backdrop', $attributes['backdrop_path']);
        $this->syncCreditsFromTmdb($attributes['credits']);
    }

    protected function syncCollectionFromTmdb($tmdb_collection)
    {
        if (is_null($tmdb_collection)) {
            return;
        }

        $collection = Collection::firstOrCreate([
            'id' => $tmdb_collection['id'],
        ], [
            'name' => $tmdb_collection['name'],
        ]);

        if ($tmdb_collection['poster_path']) {
            Image::createFromTmdb([
                'type' => 'poster',
                'path' => $tmdb_collection['poster_path'],
                'medium_type' => Collection::class,
                'medium_id' => $collection->id,
            ]);
        }

        if ($tmdb_collection['backdrop_path']) {
            Image::createFromTmdb([
                'type' => 'backdrop',
                'path' => $tmdb_collection['backdrop_path'],
                'medium_type' => Collection::class,
                'medium_id' => $collection->id,
            ]);
        }

        $this->update([
            'collection_id' => $collection->id,
        ]);
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function setSlug() : void
    {
        if (! $this->isDirty('name')) {
            return;
        }

        $this->attributes['slug'] = (is_null($this->name) ? Str::uuid() : Str::slug($this->name . '-' . $this->year, '-', 'de'));
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

    public function collection() : BelongsTo
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    public function scopeSearch(Builder $query, $value) : Builder
    {
        if (empty($value)) {
            return $query;
        }

        return $query->where('name', 'LIKE', '%' . $value . '%');
    }
}