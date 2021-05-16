<?php

namespace App\Traits\Media;

use App\Models\Movies\Collection;
use App\Models\Movies\Movie;
use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Show;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasCard
{
    protected $progress;

    public static function bootHasCard()
    {
        static::creating(function ($model) {
            //
        });
    }

    public static function mediaType() : string
    {
        return Str::plural(strtolower(class_basename(self::class)));
    }

    public function initializeHasCard()
    {
        //
    }

    public function toCard() : self
    {
        if (Auth::check()) {
            $this->loadCount([
                'watched' => function ($query) {
                    return $query->where('user_id', Auth::id());
                }
            ]);

            $this->rating_by_user = $this->ratingByUser(Auth::id());

            if ($this->is_show) {

                $last_watched = auth()->user()->last_watched()->with([
                    'watchable',
                ])->where('show_id', $this->id)->first();

                if ($last_watched) {
                    $this->next_episode = Episode::with([
                        'season',
                    ])->nextByAbsoluteNumber($last_watched->watchable->show_id, $last_watched->watchable->absolute_number)->first();
                }

            }

        }
        else {
            $this->watched_count = 0;
        }

        $this->append([
            'class_name',
            'is_collection',
            'is_episode',
            'is_movie',
            'is_show',
            'itemtype',
            'poster_url',
            'backdrop_url',
            'progress',
            'watched_path',
            'vote_average_formatted',
            'path',
            'rate_path',
            'watched_event_name',
        ]);

        if ($this->is_episode) {
            $this->show->append('path');
        }
        else {
            $this->append([
                'lists_path',
            ]);
        }

        return $this;
    }

    public function isClass(string $class_name)
    {
        return (get_class($this) == $class_name);
    }

    public function getClassNameAttribute() : string
    {
        return strtolower(class_basename($this));
    }

    public function getMediaTypeAttribute() : string
    {
        return Str::plural($this->class_name);
    }

    public function getIsCollectionAttribute() : bool
    {
        return $this->isClass(Collection::class);
    }

    public function getIsEpisodeAttribute() : bool
    {
        return $this->isClass(Episode::class);
    }

    public function getIsMovieAttribute() : bool
    {
        return $this->isClass(Movie::class);
    }

    public function getIsShowAttribute() : bool
    {
        return $this->isClass(Show::class);
    }

    public function getItemtypeAttribute() : string
    {
        if ($this->is_movie) {
            return 'http://schema.org/Movie';
        }
        elseif ($this->is_show) {
            return 'http://schema.org/TVSeries';
        }
        elseif ($this->is_episode) {
            return 'http://schema.org/TVEpisode';
        }
    }

    public function getWatchedEventNameAttribute() : string
    {
        return $this->class_name . '_' . $this->id . '_watched';
    }

    public function getVoteAverageFormattedAttribute() : string
    {
        return number_format($this->vote_average, (in_array($this->vote_average, [0, 10]) ? 0 : 1) , ',', '');
    }

    public function getProgressAttribute() : array
    {
        if ($this->progress) {
            return $this->progress;
        }

        $watchable_count = ($this->is_show ? $this->episodes_count : 1);
        if (auth()->check()) {
            $watched_count = $this->watchedByUser(auth()->user()->id)->count('watchable_id');
            $watched_distinct_count = $this->watchedByUser(auth()->user()->id)->distinct()->count('watchable_id');
            return $this->progress = [
                'watched_count' => $watched_count,
                'watched_distinct_count' => $watched_distinct_count,
                'percent' => ($watchable_count == 0 ? 0 : min(100, round($watched_distinct_count / $watchable_count * 100, 0))),
                'watchable_count' => $watchable_count,
            ];
        }

        return $this->progress = [
            'watched_count' => 0,
            'watched_distinct_count' => 0,
            'percent' => 0,
            'watchable_count' => $watchable_count,
        ];
    }

    public function scopeCardForUser(Builder $query, $user_id) : Builder
    {
        if (is_null($user_id)) {
            return $query;
        }

        return $query->select('*', DB::raw($user_id . ' AS card_user_id'));
    }
}