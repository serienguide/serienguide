<?php

namespace App\Http\Livewire\Movies;

use App\Models\Movies\Movie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
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
            'items' => $this->getItems(),
        ]);
    }

    protected function getItems() : LengthAwarePaginator
    {
        $query = Movie::search($this->filter['search'])
            ->orderBy('title', 'ASC');

        if (auth()->check()) {
            $query->with([
                'watched' => function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                }
            ]);
        }

        return $query->paginate(12);
    }
}
