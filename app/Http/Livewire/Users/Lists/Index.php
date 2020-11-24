<?php

namespace App\Http\Livewire\Users\Lists;

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $user;

    public function mount($user)
    {
        $this->user = $user;
    }

    protected function getItems() : LengthAwarePaginator
    {
        return $this->user
            ->lists()
            ->orderBy('name', 'ASC')
            ->paginate();
    }

    public function render()
    {
        return view('livewire.users.lists.index')
            ->with('items', $this->getItems());
    }
}
