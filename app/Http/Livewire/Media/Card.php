<?php

namespace App\Http\Livewire\Media;

use App\Models\Shows\Show;
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

    public function rate(int $rating)
    {
        $this->model->rating_by_user = $this->model->rateBy(auth()->user(), ['rating' => $rating]);
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
        return view('livewire.media.card')
            ->with('button_class', $this->buttonClass());
    }

    protected function buttonClass() : string
    {
        if ($this->model->watched->count() == 0) {
            return 'text-gray-700 bg-white';
        }

        if ($this->model->watched->count() % 2 == 0) {
            return 'bg-green-600 text-white';
        }

        return 'bg-indigo-600 text-white';
    }
}
