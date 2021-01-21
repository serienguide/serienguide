<?php

namespace App\Http\Livewire\Users\Profiles;

use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Show;
use App\Models\User;
use App\Models\Watched\Watched;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Progress extends Component
{
    use WithPagination;

    public $user;
    public $last_date;
    public $daily_runtimes = [];
    public $sort_by = 'max_watched_at';
    public $sort_direction = 'DESC';

    public $filter = [
        'watchable_type' => 0,
    ];

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function UpdatingFilter()
    {
        $this->resetPage();
    }

    public function UpdatingSortBy()
    {
        $this->resetPage();
    }

    public function UpdatingSortDirection()
    {
        $this->resetPage();
    }

    public function toggleSortDirection()
    {
        if ($this->sort_direction == 'DESC') {
            $this->sort_direction = 'ASC';
        }
        elseif ($this->sort_direction == 'ASC') {
            $this->sort_direction = 'DESC';
        }
    }

    public function getItems()
    {
        $per_page = 12;
        $results_total = Watched::where('user_id', $this->user->id)->where('watchable_type', Episode::class)->distinct('show_id')->count('show_id');

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
                    " . $this->sort_by . " " . $this->sort_direction . ", name ASC
                LIMIT
                    :start, :limit";

        $results = DB::select($sql, [
            'watchable_type' => Episode::class,
            'user_id' => $this->user->id,
            'start' => ($this->resolvePage() - 1) * $per_page,
            'limit' => $per_page,
        ]);

        $items = [];
        foreach ($results as $key => $result) {
            $show = Show::find($result->id);
            $show->user = $this->user;
            $show->append([
                'last_watched',
                'next_episode_to_watch',
                'progress',
            ]);
            $result->show = $show;
            $items[] = $result;
        }

        return new LengthAwarePaginator($items, $results_total, $per_page);
    }

    public function render()
    {
        return view('livewire.users.profiles.progress')
            ->with('items', $this->getItems());
    }
}
