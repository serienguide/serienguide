<?php

namespace App\Http\Livewire\Dashboard\Following;

use App\Models\User;
use App\Models\Watched\Watched;
use Livewire\Component;
use Livewire\WithPagination;

class LastWatched extends Component
{
    use WithPagination;

    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function UpdatingFilter()
    {
        $this->resetPage();
    }

    public function UpdatingSortBy()
    {
        $this->resetPage();
    }

    public function getItems()
    {
        $items = Watched::with([
                'user',
                'watchable',
            ])
            ->whereIn('user_id', $this->user->followings->pluck('id'))
            ->latest('watched_at')
            ->paginate(12);

        return $items;
    }

    public function render()
    {
        return view('livewire.dashboard.following.last-watched')
            ->with('items', $this->getItems());
    }
}
