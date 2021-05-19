<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Models\Movies\Movie;
use App\Models\Shows\Episodes\Episode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request, int $year = null, int $week = null)
    {
        $now = now();
        $start_of_week = $now->setISODate($year ?? $now->year, str_pad($week ?? $now->week, 2, '0', STR_PAD_LEFT))->startOfWeek();
        $end_of_week = $start_of_week->clone()->endOfWeek();
        $previous_week = $start_of_week->clone()->subDays(7);
        $next_week = $start_of_week->clone()->addDays(7);

        if ($request->wantsJson()) {
            switch ($request->input('watchable_type')) {
                case \App\Models\Movies\Movie::class:
                    $models = $this->getMovies($start_of_week);
                    $sort_by = 'released_at';
                    break;
                case \App\Models\Shows\Episodes\Episode::class:
                    $models = $this->getEpisodes($start_of_week);
                    $sort_by = 'first_aired_at';
                    break;

                default:
                    abort(404);
                    break;
            }

            $dates = [];
            $last_date = null;
            $i = -1;
            foreach ($models as $model) {
                $model->toCard();
                if (is_null($last_date) || $last_date->format('Ymd') != $model->$sort_by->format('Ymd')) {

                    if ($i > 0) {
                        $dates[$i]['h'] = floor($dates[$i]['runtime'] / 60);
                        $dates[$i]['m'] = $dates[$i]['runtime'] % 60;
                    }

                    $last_date = $model->$sort_by;
                    $i++;

                    $dates[$i] = [
                        'models' => [],
                        'runtime' => 0,
                        'title' => $last_date->dayName . ', ' . $last_date->format('d.') . ' ' . $last_date->monthName . ' ' . $last_date->format('Y'),
                        'h' => 0,
                        'm' => 0,
                    ];
                }
                $dates[$i]['models'][] = $model;
                $dates[$i]['runtime'] += $model->runtime;
            }

            if ($models->count()) {
                $dates[$i]['h'] = floor($dates[$i]['runtime'] / 60);
                $dates[$i]['m'] = $dates[$i]['runtime'] % 60;
            }

            return [
                'models' => $models,
                'dates' => $dates,
            ];

        }

        $watchable_types = [
            \App\Models\Movies\Movie::class => \App\Models\Movies\Movie::label(),
            \App\Models\Shows\Episodes\Episode::class => \App\Models\Shows\Episodes\Episode::label(),
        ];

        return view('calendar.index')
            ->with('start_of_week', $start_of_week)
            ->with('end_of_week', $end_of_week)
            ->with('previous_week', $previous_week)
            ->with('next_week', $next_week)
            ->with('watchable_types', $watchable_types)
            ->with('title', 'KW ' . str_pad($start_of_week->week, 2, '0', STR_PAD_LEFT) . ' ' . $start_of_week->year . ' von ' . $start_of_week->format('d.m.Y') . ' - ' . $end_of_week->format('d.m.Y'))
            ->with('html_attributes', [
                'title' => 'Serienkalender für die KW ' . str_pad($start_of_week->week, 2, '0', STR_PAD_LEFT) . ' ' . $start_of_week->year,
                'description' => 'Kalender mit allen Ausstrahlungsdaten'
            ]);
    }

    protected function getEpisodes(Carbon $start_of_week)
    {
        return Episode::with([
                'show',
                'season'
            ])
            ->whereRaw('YEARWEEK(first_aired_at, 3) = ' . $start_of_week->year . str_pad($start_of_week->week, 2, '0', STR_PAD_LEFT))
            ->orderBy('first_aired_at', 'ASC')
            ->orderBy('show_id')
            ->orderBy('absolute_number')
            ->get();
    }

    protected function getMovies(Carbon $start_of_week)
    {
        return Movie::with([
                //
            ])
            ->whereRaw('YEARWEEK(released_at, 3) = ' . $start_of_week->year . str_pad($start_of_week->week, 2, '0', STR_PAD_LEFT))
            ->orderBy('released_at', 'ASC')
            ->orderBy('name', 'ASC')
            ->get();
    }
}
