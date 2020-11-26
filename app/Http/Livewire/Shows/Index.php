<?php

namespace App\Http\Livewire\Shows;

use App\Models\Shows\Show;
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
        return view('livewire.shows.index', [
            'items' => $this->getItems(),
        ]);
    }

    protected function getItems() : LengthAwarePaginator
    {
        $query = Show::search($this->filter['search'])
            ->orderBy('name', 'ASC');

        if (auth()->check()) {
            // $query->with([
            //     'watched' => function ($query) {
            //         return $query->where('user_id', auth()->user()->id);
            //     }
            // ]);
        }

        $items = $query->paginate(12);
        if (auth()->check()) {
            foreach ($items as $key => &$item) {
                $item->rating_by_user = $item->ratingByUser(auth()->user()->id);
            }
        }

        return $items;
    }
}
