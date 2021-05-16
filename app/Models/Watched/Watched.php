<?php

namespace App\Models\Watched;

use App\Models\Shows\Episodes\Episode;
use App\Models\User;
use App\Traits\BelongsToUser;
use Carbon\Carbon;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Watched extends Model
{
    use BelongsToUser, HasFactory, HasModelPath;

    const ROUTE_NAME = 'media.watched';
    const VIEW_PATH = 'watched';

    protected $appends = [
        'class_name',
        'created_at_formatted',
        'path',
        'watched_at_diff_for_humans',
        'watched_at_formatted',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'watched_at',
    ];

    protected $fillable = [
        'user_id',
        'show_id',
        'watchable_id',
        'watchable_type',
        'watched_at',
    ];

    protected $table = 'watched';

    public static function getLatestEpisodeIdsByShow(int $user_id, int $page = 0)
    {
        $limit = 6;
        $start = ($page ? $page * $limit : 0);
        $sql = "SELECT * FROM (
                    SELECT
                        watched.show_id,
                        MAX(watched.watched_at) AS latest
                    FROM
                        watched
                    WHERE
                        watched.user_id = :user_id AND
                        watched.watchable_type = :episode_class
                    GROUP BY
                        watched.show_id
                    ORDER BY
                        watched.watched_at DESC
                ) AS a
                    JOIN watched ON (watched.id = (SELECT b.id FROM watched AS b WHERE b.show_id = a.show_id AND b.watched_at = a.latest ORDER BY b.id DESC LIMIT 1))
                    JOIN shows ON (shows.id = a.show_id)
                    LEFT JOIN episodes ON (episodes.id = watched.watchable_id AND episodes.show_id = a.show_id AND episodes.absolute_number < shows.episodes_count)
                WHERE
                    episodes.id IS NOT NULL
                ORDER BY
                    a.latest DESC
                LIMIT
                    :start, :limit";

        return DB::select($sql, [
            'user_id' => $user_id,
            'start' => $start,
            'limit' => $limit,
            'episode_class' => Episode::class,
        ]);
    }

    public static function getNextEpisodes(int $user_id, int $page = 0) : Collection
    {
        $episodes = new Collection();
        $latest_episodes = self::getLatestEpisodeIdsByShow($user_id, $page);

        if (empty($latest_episodes)) {
            return $episodes;
        }

        foreach ($latest_episodes as $key => $latest_episode) {
            $next_episode = Episode::nextByAbsoluteNumber($latest_episode->show_id, $latest_episode->absolute_number)->first();
            if (is_null($next_episode)) {
                continue;
            }
            $episodes->push($next_episode);
        }

        return $episodes;
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function getClassNameAttribute() : string
    {
        return strtolower(class_basename($this));
    }

    public function getWatchedAtFormattedAttribute() : string
    {
        return $this->watched_at->format('d.m.Y H:i');
    }

    public function setWatchedAtFormattedAttribute(string $value) : void
    {
        $this->attributes['watched_at'] = Carbon::createFromFormat('d.m.Y H:i', $value);
        Arr::forget($this->attributes, 'watched_at_formatted');
    }

    public function getWatchedAtDiffForHumansAttribute() : string
    {
        return $this->watched_at->diffForHumans();
    }

    public function getCreatedAtFormattedAttribute() : string
    {
        return $this->created_at->format('d.m.Y H:i');
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'media_type' => $this->watchable_type::ROUTE_NAME,
            'model' => $this->watchable_id,
            'watched' => $this->id,
        ];
    }

    public function watchable() : MorphTo
    {
        return $this->morphTo();
    }

    public function scopeWatchableType(Builder $query, $value) : Builder
    {
        if (! $value) {
            return $query;
        }

        return $query->where('watchable_type', $value);
    }
}