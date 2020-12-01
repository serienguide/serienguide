<?php

namespace App\Models\Shows\Episodes;

use App\Models\Shows\Seasons\Season;
use App\Models\Shows\Show;
use App\Models\User;
use App\Models\Watched\Watched;
use App\Traits\Media\HasCard;
use App\Traits\Media\HasCredits;
use App\Traits\Media\HasImages;
use App\Traits\Media\HasRatings;
use App\Traits\Media\HasWatched;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Episode extends Model
{
    use HasCard,
        HasCredits,
        HasFactory,
        HasImages,
        HasRatings,
        // HasModelPath,
        HasWatched,
        SoftDeletes;

    const ROUTE_NAME = '';
    const VIEW_PATH = '';

    protected $appends = [
        //
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'first_aired_at',
    ];

    protected $fillable = [
        'deleted_at',
        'episode_number',
        'first_aired_at',
        'name',
        'name_en',
        'overview',
        'production_code',
        'season_id',
        'show_id',
        'still_path',
        'tmdb_id',
        'tvdb_id',
        'vote_count',
        'vote_average',
        'tmdb_vote_count',
        'tmdb_vote_average',
    ];

    public function isDeletable() : bool
    {
        return true;
    }

    public function updateFromTmdb($attributes = null)
    {
        if (is_null($attributes)) {
            $attributes = \App\Apis\Tmdb\Shows\Episodes\Episode::find($this->show->tmdb_id, $this->season->season_number, $this->episode_number);
        }
        if (empty($attributes)) {
            return;
        }
        $this->update($attributes);
        $this->syncFromTmdb($attributes);
    }

    protected function syncFromTmdb($attributes)
    {
        $this->createImageFromTmdb('still', $attributes['still_path']);
        $this->syncCreditsFromTmdb([
            'crew' => $attributes['crew'],
            'guest_stars' => $attributes['guest_stars'],
        ]);
    }

    public function getPosterPathAttribute() : string
    {
        return $this->show->poster_path;
    }

    public function getBackdropPathAttribute() : string
    {
        return $this->still_path ?: $this->show->backdrop_path;
    }

    public function watchedBy(User $user, array $attributes = []) : Watched
    {
        return $this->watched()->create([
            'user_id' => $user->id,
            'watched_at' => Arr::get($attributes, 'watched_at', now()),
            'show_id' => $this->show_id,
        ]);
    }

    public function season() : BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id');
    }

    public function show() : BelongsTo
    {
        return $this->belongsTo(Show::class, 'show_id');
    }

    public function scopeNextByAbsoluteNumber(Builder $query, int $show_id, int $absolute_number) : Builder
    {
        if (! $absolute_number) {
            return $query;
        }

        return $query->where('absolute_number', '>', $absolute_number)
            ->where('show_id', $show_id)
            ->orderBy('absolute_number', 'ASC')
            ->take(1);
    }

    public function scopeNextEpisodes(Builder $query, int $user_id) : Builder
    {
        return $query->from('watched')->select('next_episodes.*')
            ->join('episodes', 'episodes.id', '=', 'watched.watchable_id')
            ->join('episodes AS next_episodes', function ($join) {
                return $join->on('next_episodes.show_id', '=', 'episodes.show_id')
                    ->on('next_episodes.absolute_number', '>', 'episodes.absolute_number')
                    ->take(1)
                    ->orderBy('episodes.absolute_number');
            })
            ->where('watched.user_id', $user_id)
            ->where('watched.watchable_type', Episode::class)
            ->groupBy('episodes.show_id')
            ->orderBy('watched.watched_at', 'DESC');
    }
}