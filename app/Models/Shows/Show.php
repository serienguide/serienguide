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
use Illuminate\Support\Facades\DB;
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
        'air_day',
        'air_time',
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
        $attributes = \App\Apis\Tmdb\Shows\Show::find($tmdb_id);
        $model = self::updateOrCreate([
            'tmdb_id' => $attributes['id'],
        ], $attributes);

        $model->syncFromTmdb($attributes);

        return $model;
    }

    public function updateFromTmdb()
    {
        $attributes = \App\Apis\Tmdb\Shows\Show::find($this->tmdb_id);
        if (empty($attributes)) {
            return;
        }
        $this->update($attributes);
        $this->syncFromTmdb($attributes);
    }

    protected function syncFromTmdb($attributes)
    {
        $this->syncGenresFromTmdb($attributes['genres']);
        $this->syncKeywordsFromTmdb($attributes['keywords']);
        $this->syncProvidersFromTmdb($attributes['providers']);
        $this->createImageFromTmdb('poster', $attributes['poster_path']);
        $this->createImageFromTmdb('backdrop', $attributes['backdrop_path']);
        $this->syncCreditsFromTmdb($attributes['credits']);
        $this->syncSeasonsFromTmdb($attributes['seasons']);
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

    public function updateFromGlotz()
    {
        if (empty($this->tvdb_id)) {
            return;
        }

        $glotz_show = \App\Apis\Glotz\Show::find($this->tvdb_id);

        if (empty($glotz_show)) {
            return;
        }

        $this->update([
            'air_day' => ($glotz_show['airs_day'] ?: null),
            'air_time' => ($glotz_show['airs_time'] == '00:00:00' ? null : $glotz_show['airs_time']),
        ]);
    }

    public function setCounts() : void
    {
        $this->update([
            'episodes_count' => $this->episodes()->count(),
            'seasons_count' => $this->seasons()->count(),
        ]);
    }

    public function setAbsoluteNumbers() : void
    {
        $sql = "SELECT
                    shows.name,
                    seasons.season_number,
                    episodes.episode_number,
                    episodes.id
                FROM
                    episodes,
                    seasons,
                    shows
                WHERE
                    episodes.deleted_at IS NULL AND
                    seasons.id = episodes.season_id AND
                    seasons.deleted_at IS NULL AND
                    shows.id = seasons.show_id AND
                    shows.id = :show_id AND
                    seasons.season_number > 0 AND
                    episodes.episode_number > 0
                ORDER BY
                    seasons.season_number ASC,
                    episodes.episode_number ASC";

        $episodes = DB::select($sql, [
            'show_id' => $this->id,
        ]);

        $absolute_number = 0;
        foreach ($episodes as $key => $episode) {
            $absolute_number++;
            Episode::where('id', $episode->id)->update([
                'absolute_number' => $absolute_number,
            ]);
        }
    }

    public function getLastWatchedAttribute()
    {
        if (Arr::has($this->attributes, 'last_watched')) {
            return $this->attributes['last_watched'];
        }

        if (! auth()->check()) {
            return null;
        }

        return $this->attributes['last_watched'] = $this->watched()->where('user_id', auth()->user()->id)->latest('watched_at')->orderBy('id', 'DESC')->first();
    }

    public function getCurrentSeasonNumberAttribute()
    {
        if (Arr::has($this->attributes, 'current_season_number')) {
            return $this->attributes['current_season_number'];
        }

        if (! auth()->check() || is_null($this->last_watched)) {
            return $this->attributes['current_season_number'] = 1;
        }

        return $this->attributes['current_season_number'] = ($this->next_episode_to_watch ? $this->next_episode_to_watch->season->season_number : $this->last_watched->watchable->season->season_number);
    }

    public function getNextEpisodeToWatchAttribute()
    {
        if (Arr::has($this->attributes, 'next_episode_to_watch')) {
            return $this->attributes['next_episode_to_watch'];
        }

        if (! auth()->check()) {
            return null;
        }

        $absolute_number = is_null($this->last_watched) ? 0 : $this->last_watched->watchable->absolute_number;

        return $this->attributes['next_episode_to_watch'] = Episode::nextByAbsoluteNumber($this->id, $absolute_number)->first();
    }

    public function getLastAiredEpisodesAttribute()
    {
        if (Arr::has($this->attributes, 'last_aired_episodes')) {
            return $this->attributes['last_aired_episodes'];
        }

        return $this->attributes['last_aired_episodes'] = $this->episodes()
            ->whereNotNull('first_aired_at')
            ->where('first_aired_at', '<=', today()->format('Y-m-d'))
            ->orderBy(DB::raw('IFNULL(first_aired_en_at, first_aired_de_at)'), 'DESC')
            ->orderBy('absolute_number', 'DESC')
            ->take(is_null($this->next_episode_to_watch) ? 4 : 3)
            ->get();
    }

    public function seasons() : HasMany
    {
        return $this->hasMany(Season::class, 'show_id')->orderBy('season_number', 'ASC');
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
        $this->attributes['slug'] = (is_null($this->name) ? Str::uuid() : Str::slug($this->name . '-' . $this->year, '-', 'de'));
    }

    public function scopeSearch(Builder $query, $value) : Builder
    {
        if (empty($value)) {
            return $query;
        }

        return $query->where('name', 'LIKE', '%' . $value . '%');
    }
}