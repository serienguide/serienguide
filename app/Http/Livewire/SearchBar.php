<?php

namespace App\Http\Livewire;

use App\Models\Movies\Movie;
use App\Models\Shows\Show;
use Livewire\Component;

class SearchBar extends Component
{
    public $query;
    public $results;
    public $highlightIndex;

    public function mount()
    {
        $this->reset();
    }

    public function reset(...$properties)
    {
        $this->query = '';
        $this->results = [];
        $this->highlightIndex = 0;
    }

    public function updatedQuery()
    {
        $this->results = [];

        if (strlen($this->query) < 3) {
            return;
        }

        $models = $this->getShows();
        if ($models->count()) {
            $this->results['shows'] = $models;
        }

        $models = $this->getMovies();
        if ($models->count()) {
            $this->results['movies'] = $models;
        }

    }

    protected function getMovies()
    {
        return Movie::search($this->query)
            ->orderBy('name', 'ASC')
            ->take(10)
            ->get();
    }

    protected function getShows()
    {
        return Show::search($this->query)
            ->orderBy('name', 'ASC')
            ->take(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.search-bar');
    }
}