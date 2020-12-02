<?php

namespace App\Http\Livewire\Shows\Seasons;

use App\Models\Shows\Seasons\Season;
use Livewire\Component;

class Index extends Component
{
    public $season;
    public $items;
    public $isCurrent = false;

    public function mount(Season $season)
    {
        $this->season = $season;
        if ($this->isCurrent) {
            $this->load();
        }
    }

    public function watch()
    {
        $watched = $this->season->watchedBy(auth()->user());
        $this->emit('watched_season_' . $this->season->id);
        if (is_null($this->items)) {
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
