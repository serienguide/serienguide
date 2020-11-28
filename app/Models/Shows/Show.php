<?php

namespace App\Models\Shows;

use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Seasons\Season;
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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Show extends Model
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

    const ROUTE_NAME = 'shows';
    const VIEW_PATH = 'shows';

    protected $appends = [
        //
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'first_aired_at',
        'last_aired_at',
    ];

    protected $fillable = [
        'backdrop_path',
        'episodes_count',
        'facebook',
        'first_aired_at',
        'homepage',
        'imdb_id',
        'instagram',
        'is_anime',
        'last_aired_at',
        'name',
        'name_en',
        'original_language',
        'overview',
        'poster_path',
        'runtime',
        'seasons_count',
        'status',
        'tagline',
        'tmdb_id',
        'tvdb_id',
        'twitter',
        'type',
        'year',
        'vote_count',
        'vote_average',
        'tmdb_vote_count',
        'tmdb_vote_average',
    ];

    public static function createOrUpdateFromTmdb(int $tmdb_id) : self
    {
        $tmdb_model = \App\Apis\Tmdb\Shows\Show::find($tmdb_id);
        $attributes = array_filter($tmdb_model->only((new self())->getFillable()));
        $model = self::updateOrCreate([
            'tmdb_id' => $tmdb_model->id,
        ], $attributes);

        $model->syncFromTmdb($tmdb_model);

        return $model;
    }

    public function updateFromTmdb(int $tmdb_id = 0)
    {
        $tmdb_model = \App\Apis\Tmdb\Shows\Show::find($tmdb_id ?: $this->tmdb_id);
        $this->update($tmdb_model->toArray());
        $this->syncFromTmdb($tmdb_model);
    }

    protected function syncFromTmdb($tmdb_model)
    {
        $this->syncGenresFromTmdb($tmdb_model->genres);
        $this->syncKeywordsFromTmdb($tmdb_model->keywords);
        $this->syncProvidersFromTmdb($tmdb_model->providers);
        $this->createImageFromTmdb('poster', $tmdb_model->poster_path);
        $this->createImageFromTmdb('backdrop', $tmdb_model->backdrop_path);
        $this->syncCreditsFromTmdb($tmdb_model->credits);
        $this->syncSeasonsFromTmdb($tmdb_model->seasons);
    }

    protected function syncSeasonsFromTmdb($tmdb_seasons)
    {
        $this->seasons()->delete();
        foreach ($tmdb_seasons as $tmdb_season) {
            $season = $this->seasons()->withTrashed()->updateOrCreate([
                'season_number' => $tmdb_season['season_number'],
            ], $tmdb_season + [
                'deleted_at' => null,
            ]);
            $season->createImageFromTmdb('poster', $tmdb_season['poster_path']);
        }
    }

    public function seasons() : HasMany
    {
        return $this->hasMany(Season::class, 'show_id');
    }

    public function episodes() : HasMany
    {
        return $this->hasMany(Episode::class, 'show_id');
    }

    public function watchedBy(User $user, array $attributes = []) : void
    {
        foreach ($this->episodes as $episode) {
            $episode->watched()->create([
                'user_id' => $user->id,
                'watched_at' => Arr::get($attributes, 'watched_at', now()),
                'show_id' => $this->id,
            ]);
        }
    }

    public function watched() : HasMany
    {
        return $this->hasMany(Watched::class, 'show_id');
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function setSlug() : void
    {
        if (is_null($this->name)) {
            return;
        }

        $this->attributes['slug'] = Str::slug($this->name . ($this->year ? '-' . $this->year : ''), '-', 'de');
    }

    public function scopeSearch(Builder $query, $value) : Builder
    {
        if (empty($value)) {
            return $query;
        }

        return $query->where('name', 'LIKE', '%' . $value . '%');
    }
}