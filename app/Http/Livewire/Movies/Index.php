<?php

namespace App\Http\Livewire\Movies;

use App\Models\Movies\Movie;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $filter = [
        'search' => '',
    ];

    public function mount()
    {
        //
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.movies.index', [
            'items' => Movie::withCount('watched')
                ->search($this->filter['search'])
                ->orderBy('title', 'ASC')
                ->paginate(12),
        ]);
    }
}
