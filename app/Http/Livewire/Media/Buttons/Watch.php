<?php

namespace App\Http\Livewire\Media\Buttons;

use Livewire\Component;

class Watch extends Component
{
    public $model;

    protected function getListeners()
    {
        $listeners = [
            //
        ];

        if ($this->model->is_episode) {
            $listeners['watched_episode_' . $this->model->id] = 'watched';
            $listeners['watched_season_' . $this->model->season_id] = 'watched';
            $listeners['watched_show_' . $this->model->show_id] = 'watched';

            $listeners['unwatched_episode_' . $this->model->id] = 'unwatched';
        }

        if ($this->model->is_movie) {
            $listeners['watched_movie_' . $this->model->id] = 'watched';
            $listeners['unwatched_movie_' . $this->model->id] = 'unwatched';
        }

        return $listeners;
    }

    public function mount($model)
    {
        $this->model = $model;
        $this->loadWatchedCount();
    }

    public function watch()
    {
        $watched = $this->model->watchedBy(auth()->user());
        $this->loadWatchedCount();

        if ($this->model->is_episode) {
            $this->emit('watched_episode_' . $this->model->id);
            $this->emit('watched_episode_season_' . $this->model->season_id);
            $this->emit('watched_episode_show_' . $this->model->show_id);
        }
        elseif ($this->model->is_movie) {
            $this->emit('watched_movie_' . $this->model->id);
        }
        elseif ($this->model->is_show) {
            $this->emit('watched_show_' . $this->model->id);
        }
    }

    public function watched()
    {
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

    public function render()
    {
        return view('livewire.media.buttons.watch')
            ->with('button_class', $this->buttonClass());
    }
}
