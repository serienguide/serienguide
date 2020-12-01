<?php

namespace App\Http\Livewire\Shows\Episodes;

use App\Models\Movies\Collection;
use App\Models\Shows\Episodes\Episode;
use App\Models\Watched\Watched;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class Next extends Component
{
    public $items;
    public $page = 0;

    public function mount()
    {
        //
    }

    public function nextPage()
    {
        $this->page++;
        $this->loadItems();
    }

    public function previousPage()
    {
        $this->page--;
        if ($this->page < 0) {
            $this->page = 0;
        }
        $this->loadItems();
    }

    public function render()
    {
        return view('livewire.shows.episodes.next');
    }

    public function loadItems() : void
    {
        $this->items = Watched::getNextEpisodes(auth()->user()->id, $this->page);
    }
}
