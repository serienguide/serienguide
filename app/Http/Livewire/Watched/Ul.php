<?php

namespace App\Http\Livewire\Watched;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Ul extends Component
{
    public $model;
    public $items;

    protected function getListeners()
    {
        $listeners = [
            //
        ];

        if ($this->model->is_episode) {
            $listeners['watched_episode_' . $this->model->id] = 'watched';
        }
        elseif ($this->model->is_movie) {
            $listeners['watched_movie_' . $this->model->id] = 'watched';
        }

        return $listeners;
    }

    public function mount($model)
    {
        $this->model = $model;
        $this->loadWatched();
        $this->items = $this->model->watched;
    }

    public function load()
    {

    }

    public function destroy(int $index)
    {
        $items = $this->items->splice($index, 1);
        $item = $items->first();
        $item->delete();

        $this->emitUp('unwatched');
    }

    public function watch()
    {
        $watched = $this->model->watchedBy(auth()->user());
        $this->items[-1] = $watched;
        $this->emitUp('watched', $watched);
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
