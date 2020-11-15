<?php

namespace App\Http\Livewire\Media;

use App\Models\Watched\Watched;
use Livewire\Component;

class Card extends Component
{
    public $model;

    protected $listeners = [
        'watched' => 'watched',
        'unwatched' => 'unwatched',
    ];

    public function watch()
    {
        $watched = $this->model->watchedBy(auth()->user());
        $this->loadWatched();
        $this->emit('watched', $watched);
    }

    public function watched(Watched $watched)
    {
        $this->loadWatched();
    }

    public function unwatched()
    {
        $this->loadWatched();
    }

    protected function loadWatched()
    {
        $this->model->load([
            'watched' => function ($query) {
                return $query->where('user_id', auth()->user()->id);
            }
        ]);
    }

    public function render()
    {
        return view('livewire.media.card');
    }
}
