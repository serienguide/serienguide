<?php

namespace App\Http\Livewire\Media;

use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Show;
use App\Models\Watched\Watched;
use Livewire\Component;

class Card extends Component
{
    public $model;
    public $type = 'poster';
    public $load_next = false;

    protected $listeners = [
        'watched' => 'watched',
        'unwatched' => 'unwatched',
    ];

    public function mount($model)
    {
        $this->model = $model;
        $this->loadWatchedCount();
    }

    public function watch()
    {
        $watched = $this->model->watchedBy(auth()->user());
        $this->loadWatchedCount();
        // $this->emit('watched', $watched);

        if ($this->load_next) {
            $this->next();
        }
    }

    public function rate(int $rating)
    {
        $this->model->rating_by_user = $this->model->rateBy(auth()->user(), ['rating' => $rating]);
    }

    public function watched(Watched $watched)
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
        $this->loadWatchedCount();
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
}
