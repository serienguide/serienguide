<?php

namespace App\Models\Shows\Seasons;

use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Show;
use App\Models\User;
use App\Models\Watched\Watched;
use App\Traits\Media\HasImages;
use App\Traits\Media\HasWatched;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Season extends Model
{
    use HasFactory,
        HasImages,
        HasModelPath,
        HasWatched,
        SoftDeletes;

    const ROUTE_NAME = 'seasons';

    protected $appends = [
        'is_season',
        'episodes_path',
        'poster_url_sm',
        'path',
        'progress_event_name',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'first_aired_at',
    ];

    protected $fillable = [
        'tmdb_id',
        'tvdb_id',
        'overview',
        'season_number',
        'episode_count',
        'poster_path',
        'first_aired_at',
        'deleted_at',
    ];

    public function isDeletable() : bool
    {
        return true;
    }

    public function getProgressEventNameAttribute() : string
    {
        return 'season_' . $this->id . '_progress';
    }

    public function getProgressAttribute() : array
    {
        if (Arr::has($this->attributes, 'progress')) {
            return $this->attributes['progress'];
        }

        $watchable_count = $this->episode_count;
        if (auth()->check()) {
            $watched_count = $this->watchedByUser(auth()->user()->id)->distinct()->count('watchable_id');
            return $this->attributes['progress'] = [
                'watched_count' => $watched_count,
                'percent' => min(100, ($watchable_count ? round($watched_count / $watchable_count * 100, 0) : 0)),
                'watchable_count' => $watchable_count,
            ];
        }

        return $this->attributes['progress'] = [
            'watched_count' => 0,
            'percent' => 0,
            'watchable_count' => $watchable_count,
        ];
    }

    public function getClassNameAttribute() : string
    {
        return strtolower(class_basename($this));
    }

    public function getIsSeasonAttribute() : bool
    {
        return true;
    }

    public function getEpisodesPathAttribute() : string
    {
        return route('seasons.episodes.index', [
            'season' => $this->id,
        ]);
    }

    public function getMediaTypeAttribute() : string
    {
        return Str::plural($this->class_name);
    }

    public function show() : BelongsTo
    {
        return $this->belongsTo(Show::class, 'show_id');
    }

    public function episodes() : HasMany
    {
        return $this->hasMany(Episode::class, 'season_id')->orderBy('episode_number', 'ASC');
    }

    public function watchedBy(User $user, array $attributes = []) : array
    {
        $data = [];
        foreach ($this->episodes as $episode) {
            $watched = $episode->watched()->create([
                'user_id' => $user->id,
                'watched_at' => Arr::get($attributes, 'watched_at', now()),
                'show_id' => $this->show_id,
            ]);

            $data[$episode->watched_event_name] = [
                'watched' => $watched,
                'progress' => $episode->progress,
            ];
        }

        return $data;
    }

    public function watched() : HasMany
    {
        return $this->hasMany(Watched::class, 'show_id', 'show_id')
            ->join('episodes', 'episodes.id', '=', 'watched.watchable_id')
            ->where('watchable_type', Episode::class)
            ->where('episodes.season_id', $this->id);
    }

    public function updateFromTmdb()
    {
        $attributes = \App\Apis\Tmdb\Shows\Seasons\Season::find($this->show->tmdb_id, $this->season_number);
        if (empty($attributes)) {
            return;
        }
        $this->update($attributes);
        $this->syncFromTmdb($attributes);
    }

    protected function syncFromTmdb($attributes)
    {
        $this->createImageFromTmdb('poster', $attributes['poster_path']);
        $this->syncEpisodesFromTmdb($attributes['episodes']);
    }

    protected function syncEpisodesFromTmdb($tmdb_episodes)
    {
        $this->episodes()->delete();

        foreach ($tmdb_episodes as $tmdb_episode) {
            Arr::forget($tmdb_episode, ['show_id', 'season_id', 'episode_number']);
            $this->episodes()->withTrashed()->updateOrCreate([
                'show_id' => $this->show_id,
                'season_id' => $this->id,
                'episode_number' => $tmdb_episode['episode_number'],
            ], $tmdb_episode + [
                'deleted_at' => null,
            ]);
        }
    }

    public function getBackdropPathAttribute() : string
    {
        return $this->show->backdrop_path;
    }
}