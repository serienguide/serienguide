<?php

namespace App\Http\Livewire\Users\Profiles;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Followers extends Component
{
    use WithPagination;

    public $user;
    public $filter = [
        'follow_type' => 'followings',
    ];

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
        $follow_type = $this->filter['follow_type'];
        $items = $this->user->$follow_type()
            ->withCount([
                'followings',
                'followers'
            ])
            ->orderBy('name', 'ASC')
            ->paginate(12);

        return $items;
    }

    public function render()
    {
        return view('livewire.users.profiles.followers')
            ->with('items', $this->getItems());
    }
}
