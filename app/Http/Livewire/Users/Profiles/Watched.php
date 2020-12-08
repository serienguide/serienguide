<?php

namespace App\Http\Livewire\Users\Profiles;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Watched extends Component
{
    use WithPagination;

    public $user;
    public $last_date;
    public $daily_runtimes = [];
    public $sort_by = 'watched_at';

    public $filter = [
        'watchable_type' => 0,
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
        $items = $this->user->watched()
            ->with([
                'user',
                'watchable'
            ])
            ->watchableType($this->filter['watchable_type'])
            ->latest($this->sort_by)
            ->paginate(12);

        $last_date = null;
        foreach ($items as $key => $item) {
            if (is_null($last_date) || $last_date->format('Ymd') != $item->created_at->format('Ymd')) {
                $last_date = $item->created_at;
                $this->daily_runtimes[$last_date->format('Ymd')] = 0;
            }
            $this->daily_runtimes[$last_date->format('Ymd')] += $item->watchable->runtime;
        }

        return $items;
    }

    public function render()
    {
        return view('livewire.users.profiles.watched')
            ->with('items', $this->getItems());
    }
}
