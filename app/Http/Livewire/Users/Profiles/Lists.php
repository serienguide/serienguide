<?php

namespace App\Http\Livewire\Users\Profiles;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Lists extends Component
{
    use WithPagination;

    public $user;
    public $is_custom = false;

    public $filter = [
        'medium_type' => 0,
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
        $items = $this->user->lists()
            ->with([
                'user',
            ])
            ->orderBy(DB::raw('IF(lists.type IS NULL, 0, 1)'), 'DESC')
            ->orderBy('name', 'ASC')
            ->paginate(12);

        return $items;
    }

    public function render()
    {
        return view('livewire.users.profiles.lists')
            ->with('items', $this->getItems());
    }
}
