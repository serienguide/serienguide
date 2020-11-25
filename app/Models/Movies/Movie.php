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
use App\Traits\Media\HasImages;
use App\Traits\Media\MorphsToManyGenres;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Movie extends Model
{
    use HasFactory, HasImages, HasManyLists, HasModelPath, HasSlug, MorphsToManyGenres;

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
        'overview',
        'poster_path',
        'released_at',
        'revenue',
        'runtime',
        'status',
        'tagline',
        'title',
        'title_en',
        'year',
    ];

    public static function createOrUpdateFromTmdb(int $tmdb_id) : self
    {
        $tmdb_model = \App\Apis\Tmdb\Movies\Movie::find($tmdb_id);
        $model = self::updateOrCreate([
            'tmdb_id' => $tmdb_model->id,
        ], $tmdb_model->toArray());

        $model->syncFromTmdb($tmdb_model);

        return $model;
    }

    public function updateFromTmdb(int $tmdb_id)
    {
        $tmdb_model = \App\Apis\Tmdb\Movies\Movie::find($tmdb_id);
        $this->update($tmdb_model->toArray());
        $this->syncFromTmdb($tmdb_model);
    }

    protected function syncFromTmdb($tmdb_model)
    {
        $this->syncCollectionFromTmdb($tmdb_model->belongs_to_collection);
        $this->syncGenresFromTmdb($tmdb_model->genres);
        $this->syncKeywordsFromTmdb($tmdb_model->keywords);
        $this->syncProvidersFromTmdb($tmdb_model->providers);
        $this->createImageFromTmdb('poster', $tmdb_model->poster_path);
        $this->createImageFromTmdb('backdrop', $tmdb_model->backdrop_path);
        $this->syncCreditsFromTmdb($tmdb_model->credits);
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

    protected function syncCreditsFromTmdb(array $tmdb_credits)
    {
        $credit_ids = [];
        foreach ($tmdb_credits as $type => $types) {
            if (! is_array($types)) {
                continue;
            }
            foreach ($types as $key => $tmdb_credit) {
                $person = Person::firstOrCreate([
                    'id' => $tmdb_credit['id'],
                ], [
                    'name' => $tmdb_credit['name'],
                    'profile_path' => $tmdb_credit['profile_path'],
                    'known_for_department' => $tmdb_credit['known_for_department'],
                    'gender' => $tmdb_credit['gender'],
                ]);
                $this->credits()->firstOrCreate([
                    'id' => $tmdb_credit['credit_id']
                ], [
                    'person_id' => $person->id,
                    'credit_type' => $type,
                    'department' => Arr::get($tmdb_credit, 'department', ''),
                    'job' => Arr::get($tmdb_credit, 'job', ''),
                    'character' => Arr::get($tmdb_credit, 'character', ''),
                    'order' => Arr::get($tmdb_credit, 'order', 0),
                ]);
            }
        }
    }

    protected function syncGenresFromTmdb(array $tmdb_genres)
    {
        $genre_ids = [];
        foreach ($tmdb_genres as $key => $tmdb_genre) {
            $genre = Genre::firstOrCreate([
                'id' => $tmdb_genre['id'],
            ], [
                'name' => $tmdb_genre['name'],
            ]);
            $genre_ids[] = $genre->id;
        }

        $this->genres()->sync($genre_ids);
    }

    protected function syncKeywordsFromTmdb(array $tmdb_keywords)
    {
        $keyword_ids = [];
        foreach ($tmdb_keywords as $key => $tmdb_keyword) {
            $keyword = Keyword::firstOrCreate([
                'id' => $tmdb_keyword['id'],
            ], [
                'name' => $tmdb_keyword['name'],
            ]);
            $keyword_ids[] = $keyword->id;
        }

        $this->keywords()->sync($keyword_ids);
    }

    protected function syncProvidersFromTmdb(array $tmdb_providers)
    {
        $provider_ids = [];
        foreach ($tmdb_providers as $type => $types) {
            if (! is_array($types)) {
                continue;
            }
            foreach ($types as $key => $tmdb_provider) {
                $provider = Provider::firstOrCreate([
                    'id' => $tmdb_provider['provider_id'],
                ], [
                    'name' => $tmdb_provider['provider_name'],
                    'logo_path' => $tmdb_provider['logo_path'],
                ]);
                $provider_ids[$provider->id] = [
                    'display_priority' => $tmdb_provider['display_priority'],
                    'type' => $type,
                ];
            }
        }

        $this->providers()->sync($provider_ids);
    }

    protected function createImageFromTmdb(string $type, string $path) : Image
    {
        return Image::createFromTmdb([
            'type' => $type,
            'path' => $path,
            'medium_type' => self::class,
            'medium_id' => $this->id,
        ]);
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function setSlug() : void
    {
        $this->attributes['slug'] = Str::slug($this->title . '-' . $this->year, '-', 'de');
    }

    // TODO: Rateable
    public function rateBy(User $user, array $attributes = [])
    {
        if ($attributes['rating'] == 0) {
            $this->ratingByUser($user->id)->delete();
            return null;
        }

        $rating = $this->ratingByUser($user->id);
        if (! is_null($rating)) {
            $rating->update($attributes);
            return $rating;
        }

        $attributes['user_id'] = $user->id;

        return $this->ratings()->create($attributes);
    }

    // TODO: Watchable
    public function watchedBy(User $user, array $attributes = []) : Watched
    {
        return $this->watched()->create([
            'user_id' => $user->id,
            'watched_at' => Arr::get($attributes, 'watched_at', now()),
        ]);
    }

    public function collection() : BelongsTo
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    public function credits() : MorphMany
    {
        return $this->morphMany(Credit::class, 'medium');
    }

    public function keywords() : MorphToMany
    {
        return $this->morphToMany(Keyword::class, 'medium', 'keyword_medium');
    }

    public function providers() : MorphToMany
    {
        return $this->morphToMany(Provider::class, 'medium', 'provider_medium')
            ->withPivot([
                'display_priority',
                'type',
            ]);
    }

    // TODO: Rateable
    public function ratings() : MorphMany
    {
        return $this->morphMany(Rating::class, 'medium');
    }

    public function ratingByUser(int $user_id)
    {
        return $this->ratings()->where('user_id', $user_id)->first();
    }

    // TODO: Watchable
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