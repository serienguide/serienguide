<?php

namespace App\Http\Livewire\Users\Profiles;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Rated extends Component
{
    use WithPagination;

    public $user;
    public $last_date;
    public $daily_runtimes = [];
    public $sort_by = 'created_at';

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
        $items = $this->user->ratings()
            ->with([
                'user',
                'medium'
            ])
            ->mediumType($this->filter['medium_type'])
            ->latest($this->sort_by)
            ->paginate(12);

        $sort_by = $this->sort_by;
        $last_date = null;
        foreach ($items as $key => $item) {
            if (is_null($last_date) || $last_date->format('Ymd') != $item->$sort_by->format('Ymd')) {
                $last_date = $item->$sort_by;
                $this->daily_runtimes[$last_date->format('Ymd')] = 0;
            }
            $this->daily_runtimes[$last_date->format('Ymd')] += $item->medium->runtime;
        }

        return $items;
    }

    public function render()
    {
        return view('livewire.users.profiles.rated')
            ->with('items', $this->getItems());
    }
}
