<?php

namespace App\Http\Livewire\Shows\Seasons;

use App\Models\Shows\Seasons\Season;
use Livewire\Component;

class Index extends Component
{
    public $season;
    public $items;

    public function mount(Season $season)
    {
        $this->season = $season;
        if ($season->season_number == 1) {
            $this->load();
        }
    }

    public function render()
    {
        return view('livewire.shows.seasons.index');
    }

    public function load()
    {
        $this->items = $this->season->episodes()
            ->with([
                'season',
            ])
            ->orderBy('episode_number', 'ASC')
            ->get();
    }
}
