<?php

namespace App\Http\Livewire\Watched;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Ul extends Component
{
    public $model;
    public $items;
    public $action;
    public $form = [];
    public $isEditing = [];

    protected function getListeners()
    {
        $listeners = [
            //
        ];

        if ($this->model->is_episode) {
            $listeners['watched_episode_' . $this->model->id] = 'watched';
            $listeners['watched_season_' . $this->model->season_id] = 'watched';
            $listeners['watched_show_' . $this->model->show_id] = 'watched';
        }
        elseif ($this->model->is_movie) {
            $listeners['watched_movie_' . $this->model->id] = 'watched';
        }

        return $listeners;
    }

    public function mount($model, $action = null)
    {
        $this->action = $action;
        $this->model = $model;
        $this->loadWatched();
        $this->items = $this->model->watched;
        foreach ($this->items as $key => $item) {
            $this->form[$item->id] = [
                'watchedAt' => $item->watched_at->format('d.m.Y H:i'),
            ];
            $this->isEditing[$item->id] = false;
        }
    }

    public function update(int $id)
    {
        $attributes = $this->validate([
            'form.' . $id . '.watchedAt' => 'required|date_format:"d.m.Y H:i"',
        ]);

        $item = $this->items->where('id', $id)->first();
        $item->update([
            'watched_at' => Carbon::createFromFormat('d.m.Y H:i', $this->form[$id]['watchedAt']),
        ]);
        $this->isEditing[$item->id] = false;
    }

    public function destroy(int $index)
    {
        $items = $this->items->splice($index, 1);
        $item = $items->first();
        $item->delete();

        if (is_null($this->action)) {
            if ($this->model->is_episode) {
                $this->emit('unwatched_episode_' . $this->model->id);
                $this->emit('unwatched_episode_season_' . $this->model->season_id);
            }
            elseif ($this->model->is_movie) {
                $this->emit('unwatched_movie_' . $this->model->id);
            }
        }
        else {
            $this->emit('unwatched');
        }
    }

    public function watch()
    {
        $watched = $this->model->watchedBy(auth()->user());
        $this->items[-1] = $watched;
        $this->form[$watched->id] = [
            'watchedAt' => $watched->watched_at->format('d.m.Y H:i'),
        ];
        $this->isEditing[$item->id] = false;
        $this->emitUp('watched', $watched);

        if ($this->model->is_episode) {
            $this->emit('watched_episode_' . $this->model->id);
        }
        elseif ($this->model->is_movie) {
            $this->emit('watched_movie_' . $this->model->id);
        }
    }

    public function watched()
    {
        $this->loadWatched();
        $this->items = $this->model->watched;
    }

    protected function loadWatched()
    {
        $this->model->load([
            'watched' => function ($query) {
                return $query->where('user_id', auth()->user()->id)
                    ->orderBy('watched_at', 'DESC');
            }
        ]);
    }

    public function render()
    {
        return view('livewire.watched.ul');
    }
}
