<?php

namespace App\Http\Livewire\Media;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Rating extends Component
{
    public $model;
    public $user_rating;

    public function mount(Model $model)
    {
        $this->model = $model;
        if (auth()->check()) {
            $this->user_rating = $this->model->user_ratings()->first();
        }
    }

    public function rate(int $rating)
    {
        $this->user_rating = $this->model->rateBy(auth()->user(), ['rating' => $rating]);
    }

    public function render()
    {
        return view('livewire.media.rating');
    }
}
