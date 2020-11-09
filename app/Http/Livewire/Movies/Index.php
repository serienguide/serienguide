<?php

namespace App\Http\Livewire\Movies;

use App\Models\Movies\Movie;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function mount()
    {
        //
    }

    public function render()
    {
        return view('livewire.movies.index', [
            'items' => Movie::withCount('watched')
                ->orderBy('title', 'ASC')
                ->paginate(12),
        ]);
    }
}
