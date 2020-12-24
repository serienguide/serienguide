<?php

namespace App\Http\Livewire\Calendar;

use App\Models\Movies\Movie;
use App\Models\Shows\Episodes\Episode;
use Carbon\Carbon;
use Livewire\Component;

class Index extends Component
{
    public $last_date;
    public $daily_runtimes = [];
    public $start_of_week;
    public $end_of_week;
    public $items = [];
    public $sort_by;

    public $filter = [
        'watchable_type' => Episode::class,
    ];

    public function mount(Carbon $start_of_week)
    {
        $this->start_of_week = $start_of_week;
        $this->end_of_week = $start_of_week->clone()->endOfWeek();
    }

    public function UpdatedFilter()
    {
        $this->setItems();
    }

    public function setItems()
    {
        switch ($this->filter['watchable_type']) {
            case Episode::class: $this->setEpisodes(); break;
            case Movie::class: $this->setMovies(); break;
        }
    }

    protected function setEpisodes()
    {
        $this->items = Episode::with([
            'show',
            'season'
        ])
            ->whereRaw('YEARWEEK(first_aired_at, 3) = ' . $this->start_of_week->year . str_pad($this->start_of_week->week, 2, '0', STR_PAD_LEFT))
            ->orderBy('first_aired_at', 'ASC')
            ->orderBy('show_id')
            ->orderBy('absolute_number')
            ->get();

        $sort_by = 'first_aired_at';
        $this->sort_by = $sort_by;
        $last_date = null;
        foreach ($this->items as $key => $item) {
            if (is_null($last_date) || $last_date->format('Ymd') != $item->$sort_by->format('Ymd')) {
                $last_date = $item->$sort_by;
                $this->daily_runtimes[$last_date->format('Ymd')] = 0;
            }
            $this->daily_runtimes[$last_date->format('Ymd')] += $item->runtime;
        }
    }

    protected function setMovies()
    {
        $this->items = Movie::with([
            //
        ])
            ->whereRaw('YEARWEEK(released_at, 3) = ' . $this->start_of_week->year . str_pad($this->start_of_week->week, 2, '0', STR_PAD_LEFT))
            ->orderBy('released_at', 'ASC')
            ->orderBy('name', 'ASC')
            ->get();

        $sort_by = 'released_at';
        $this->sort_by = $sort_by;
        $last_date = null;
        foreach ($this->items as $key => $item) {
            if (is_null($last_date) || $last_date->format('Ymd') != $item->$sort_by->format('Ymd')) {
                $last_date = $item->$sort_by;
                $this->daily_runtimes[$last_date->format('Ymd')] = 0;
            }
            $this->daily_runtimes[$last_date->format('Ymd')] += $item->runtime;
        }
    }

    public function render()
    {
        return view('livewire.calendar.index');
    }
}
