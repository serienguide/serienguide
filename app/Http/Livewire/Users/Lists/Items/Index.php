<?php

namespace App\Http\Livewire\Users\Lists\Items;

use App\Models\Lists\Listing;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $model;

    public $filter = [
        'search' => '',
    ];

    protected $listeners = [
        'list-item-destroyed' => '$refresh',
    ];

    public function mount(Listing $model)
    {
        $this->model = $model;
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.users.lists.items.index')
            ->with('items', $this->getItems());
    }

    protected function getItems() : LengthAwarePaginator
    {
        $query = $this->model->items()
            ->with([
                'medium'
            ])
            ->latest();

        if (auth()->check()) {
            $query->with([
                'medium.watched' => function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                }
            ]);
        }

        $items = $query->paginate(12);
        if (auth()->check()) {
            foreach ($items as $key => &$item) {
                $item->medium->rating_by_user = $item->medium->ratingByUser(auth()->user()->id);
            }
        }

        return $items;
    }
}
