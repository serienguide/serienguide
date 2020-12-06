<?php

namespace App\Http\Livewire\Users\Notifications;

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $user;

    public function mount()
    {
        $this->user = auth()->user();
    }

    public function render()
    {
        return view('livewire.users.notifications.index')
            ->with('items', $this->getItems());
    }

    protected function getItems() : LengthAwarePaginator
    {
        $items = $this->user
            ->notifications()
            ->latest()
            ->paginate();

        return $items;
    }
}
