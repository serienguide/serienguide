<?php

namespace App\Http\Livewire\Movies;

use App\Models\Movies\Movie;
use Livewire\Component;

class Index extends Component
{
    public $items;

    public function mount()
    {
        $this->items = Movie::orderBy('title', 'ASC')->get();
    }

    public function render()
    {
        return view('livewire.movies.index');
    }
}
