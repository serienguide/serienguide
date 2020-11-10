<?php

namespace App\Http\Livewire\Media;

use Livewire\Component;

class Card extends Component
{
    public $model;

    public function watch()
    {
        $this->model->watchedBy(auth()->user());
        $this->model->loadCount([
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
