<?php

namespace App\Http\Livewire\Media;

use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Show;
use App\Models\Watched\Watched;
use Illuminate\Support\Arr;
use Livewire\Component;

class Card extends Component
{
    public $model;
    public $action;
    public $next_episode;
    public $type = 'poster';
    public $load_next = false;
    public $itemtype;

    public $original_listeners = [];
    public $original_model_id = 0;

    protected function getListeners()
    {
        $listeners = [];
        if ($this->model->is_episode) {
            $listeners['watched_episode_' . $this->model->id] = 'watched';
            $listeners['watched_season_' . $this->model->season_id] = 'watched';
            $listeners['watched_show_' . $this->model->show_id] = 'watched';

            $listeners['unwatched_episode_' . $this->model->id] = 'unwatched';
        }

        if ($this->model->is_movie) {
            $listeners['unwatched_movie_' . $this->model->id] = 'unwatched';
        }

        return $listeners;
    }

    public function mount($model, $action = null)
    {
        $this->model = $model;
        $this->action = $action;
        $this->loadWatchedCount();

        if (auth()->check() && $this->model->is_show) {
            $last_watched = auth()->user()->last_watched()->with([
                'watchable',
            ])->where('show_id', $this->model->id)->first();
            if ($last_watched) {
                $this->next_episode = Episode::with([
                    'season',
                ])->nextByAbsoluteNumber($last_watched->watchable->show_id, $last_watched->watchable->absolute_number)->first();
            }
        }

        $this->original_listeners = $this->getListeners();
        $this->original_model_id = $this->model->id;
    }

    protected function setItemtype()
    {
        if ($this->model->is_movie) {
            $this->itemtype = 'http://schema.org/Movie';
        }
        elseif ($this->model->is_show) {
            $this->itemtype = 'http://schema.org/TVSeries';
        }
        elseif ($this->model->is_episode) {
            $this->itemtype = 'http://schema.org/TVEpisode';
        }
    }

    public function watch()
    {
        $watched = $this->model->watchedBy(auth()->user());

        if ($this->model->is_episode) {
            $this->emit('watched_episode_' . $this->model->id);
            $this->emit('watched_episode_season_' . $this->model->season_id);
            $this->emit('watched_episode_show_' . $this->model->show_id);
            if ($this->original_model_id != $this->model->id) {
                $this->watched();
            }
        }
        elseif ($this->model->is_movie) {
            $this->emit('watched_movie_' . $this->model->id);
        }
    }

    public function rate(int $rating)
    {
        $this->model->rating_by_user = $this->model->rateBy(auth()->user(), ['rating' => $rating]);
    }

    public function watched()
    {
        if ($this->load_next) {
            $this->next();
            return;
        }
        $this->loadWatchedCount();
    }

    public function unwatched()
    {
        $this->loadWatchedCount();
    }

    protected function loadWatchedCount()
    {
        if (! auth()->check()) {
            return;
        }

        $this->model->loadCount([
            'watched' => function ($query) {
                return $query->where('user_id', auth()->user()->id);
            }
        ]);
    }

    public function next()
    {
        if (! $this->model->is_episode) {
            return;
        }

        $next_episode = Episode::nextByAbsoluteNumber($this->model->show_id, $this->model->absolute_number)->first();
        if (is_null($next_episode)) {
            return;
        }

        $this->model = $next_episode;
    }

    public function render()
    {
        return view('livewire.media.card')
            ->with('button_class', $this->buttonClass());
    }

    protected function buttonClass() : string
    {
        if ($this->model->watched_count == 0) {
            return 'bg-white text-gray-700 border-gray-300 hover:text-gray-500';
        }

        if ($this->model->watched_count % 2 == 0) {
            return 'bg-green-600 text-white border-green-600 hover:bg-green-700';
        }

        return 'bg-blue-600 text-white border-blue-600 hover:bg-blue-700';
    }

    public function fireEvent($event, $params, $id)
    {
        $eventsAndHandlers = $this->getEventsAndHandlers();
        if (! Arr::has($eventsAndHandlers, $event)) {
            return;
        }
        $method = $eventsAndHandlers[$event];

        $this->callMethod($method, $params);
    }
}
