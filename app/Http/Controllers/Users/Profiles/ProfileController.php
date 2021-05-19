<?php

namespace App\Http\Controllers\Users\Profiles;

use App\Http\Controllers\Controller;
use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Show;
use App\Models\User;
use App\Models\Watched\Watched;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show(Request $request, User $user, ?string $section = 'index')
    {
        if ($request->wantsJson()) {
            switch($section) {
                case 'followers': return $this->getFollowers($request, $user); break;
                case 'lists': return $this->getLists($request, $user); break;
                case 'progress': return $this->getProgress($request, $user); break;
                case 'rated': return $this->getRated($request, $user); break;
                case 'watched': return $this->getWatched($request, $user); break;
            }

            abort(404);
        }

        $user->load([
            'last_watched.watchable',
            'followers',
        ])->loadCount([
            'followings',
        ]);

        switch ($section) {
            case 'rated':
                $filters = [
                    'medium_types' => [
                        0 => 'Alle',
                        \App\Models\Movies\Movie::class => \App\Models\Movies\Movie::label(),
                        \App\Models\Shows\Show::class => \App\Models\Shows\Show::label(),
                        \App\Models\Shows\Episodes\Episode::class => \App\Models\Shows\Episodes\Episode::label(),
                    ],
                ];
                break;
            case 'watched':
                $filters = [
                    'watchable_types' => [
                        0 => 'Alle',
                        \App\Models\Movies\Movie::class => \App\Models\Movies\Movie::label(),
                        \App\Models\Shows\Episodes\Episode::class => \App\Models\Shows\Episodes\Episode::label(),
                    ],
                ];
                break;

            default:
                $filters = [];
                break;
        }

        return view('users.profiles.show')
            ->with('index_path', $request->url())
            ->with('filters', $filters)
            ->with('section', $section)
            ->with('user', $user);
    }

    protected function getFollowers(Request $request, User $user)
    {
        $follow_type = $request->input('follow_type');

        $models = $user->$follow_type()
            ->withCount([
                'followings',
                'followers'
            ])
            ->orderBy('name', 'ASC')
            ->paginate(12);

        foreach ($models as $key => $model) {
            $model->append([
                'profile_path',
            ]);
        }

        return $models;
    }

    protected function getLists(Request $request, User $user)
    {
        $lists = $user->lists()
            ->with([
                'user',
            ])
            ->with('items', function ($query) {
                return $query->whereHas('medium', function ($medium_query) {
                    return $medium_query->whereNotNull('poster_path');
                })->take(5)
                ->with('medium');
            })
            ->orderBy(DB::raw('IF(lists.type IS NULL, 0, 1)'), 'DESC')
            ->orderBy('name', 'ASC')
            ->paginate(12);

        $last_is_custom = null;
        foreach ($lists as $key => $list) {
            $list->append([
                'path',
            ]);
            $list->user->append([
                'profile_path',
            ]);
            $list->is_first_custom_list = ($list->is_custom != $last_is_custom);
            $last_is_custom = $list->is_custom;
        }

        return $lists;
    }

    protected function getProgress(Request $request, User $user) : LengthAwarePaginator
    {
        $per_page = 12;
        $sort_by = $request->input('sort_by');
        $sort_direction = $request->input('sort_direction');

        $results_total = Watched::where('user_id', $user->id)->where('watchable_type', Episode::class)->distinct('show_id')->count('show_id');

        $sql = "SELECT
                    a.*,
                    (a.episodes_count * a.runtime) AS runtime_sum,
                    (a.watched_count / a.episodes_count * 100) AS watched_percent,
                    (a.episodes_count - (LEAST(a.watched_count, a.episodes_count))) AS episodes_left,
                    ((a.episodes_count - (LEAST(a.watched_count, a.episodes_count))) * a.runtime) AS runtime_left
                FROM
                (
                    SELECT
                        shows.id, shows.name, shows.slug, shows.poster_path, shows.episodes_count, shows.runtime, shows.tmdb_popularity, COUNT(DISTINCT watched.watchable_id) AS watched_count, MAX(watched.watched_at) AS max_watched_at
                    FROM
                        watched
                            JOIN episodes ON (episodes.id = watched.watchable_id)
                            JOIN seasons ON (seasons.id = episodes.season_id)
                            JOIN shows ON (shows.id = seasons.show_id)
                    WHERE
                        watched.watchable_type = :watchable_type AND
                        watched.user_id = :user_id
                    GROUP BY
                        shows.id
                ) AS a
                ORDER BY
                    " . $sort_by . " " . $sort_direction . ", name ASC
                LIMIT
                    :start, :limit";

        $results = DB::select($sql, [
            'watchable_type' => Episode::class,
            'user_id' => $user->id,
            'start' => ($request->input('page') - 1) * $per_page,
            'limit' => $per_page,
        ]);

        $items = [];
        foreach ($results as $key => $result) {
            $show = Show::find($result->id);
            $show->user = $user;
            $show->append([
                'last_watched',
                'next_episode_to_watch',
                'progress',
            ]);
            if (! is_null($show->next_episode_to_watch)) {
                $show->next_episode_to_watch->toCard();
            }
            $result->show = $show->toCard();
            $items[] = $result;
        }

        return new LengthAwarePaginator($items, $results_total, $per_page);
    }

    protected function getWatched(Request $request, User $user) : array
    {
        $sort_by = $request->input('sort_by');

        $models = $user->watched()
            ->with([
                'user',
                'medium',
            ])
            ->watchableType($request->input('watchable_type'))
            ->latest($sort_by)
            ->paginate(12);

        return [
            'models' => $models,
            'dates' => $this->getDates($request, $models),
        ];
    }

    protected function getRated(Request $request, User $user) : array
    {
        $sort_by = $request->input('sort_by');

        $models = $user->ratings()
            ->with([
                'user',
                'medium',
            ])
            ->mediumType($request->input('medium_type'))
            ->latest($sort_by)
            ->paginate(12);

        return [
            'models' => $models,
            'dates' => $this->getDates($request, $models),
        ];
    }

    protected function getDates(Request $request, $models) : array
    {
        $sort_by = $request->input('sort_by');

        $dates = [];
        $last_date = null;
        $i = -1;
        foreach ($models as $model) {
            $model->medium->toCard();
            if (is_null($last_date) || $last_date->format('Ymd') != $model->$sort_by->format('Ymd')) {

                if ($i > 0) {
                    $dates[$i]['h'] = floor($dates[$i]['runtime'] / 60);
                    $dates[$i]['m'] = $dates[$i]['runtime'] % 60;
                }

                $i++;
                $last_date = $model->$sort_by;

                $dates[$i] = [
                    'models' => [],
                    'runtime' => 0,
                    'title' => $last_date->dayName . ', ' . $last_date->format('d.') . ' ' . $last_date->monthName . ' ' . $last_date->format('Y'),
                    'h' => 0,
                    'm' => 0,
                ];
            }

            $dates[$i]['models'][] = $model;
            $dates[$i]['runtime'] += $model->medium->runtime;
        }

        if ($models->count()) {
            $dates[$i]['h'] = floor($dates[$i]['runtime'] / 60);
            $dates[$i]['m'] = $dates[$i]['runtime'] % 60;
        }

        return $dates;
    }
}
