<?php

namespace App\Http\Livewire\Users\Lists;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $user;
    public $is_custom = false;

    public function mount($user)
    {
        $this->user = $user;
    }

    protected function getItems() : LengthAwarePaginator
    {
        return $this->user
            ->lists()
            ->orderBy(DB::raw('IF(lists.type IS NULL, 0, 1)'), 'DESC')
            ->orderBy('name', 'ASC')
            ->paginate();
    }

    public function render()
    {
        return view('livewire.users.lists.index')
            ->with('items', $this->getItems());
    }
}
