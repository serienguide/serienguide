<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(int $year = null, int $week = null)
    {
        $now = now();
        $start_of_week = $now->setISODate($year ?? $now->year, str_pad($week ?? $now->week, 2, '0', STR_PAD_LEFT))->startOfWeek();
        $previous_week = $start_of_week->clone()->subDays(7);
        $next_week = $start_of_week->clone()->addDays(7);

        return view('calendar.index')
            ->with('start_of_week', $start_of_week)
            ->with('previous_week', $previous_week)
            ->with('next_week', $next_week)
            ->with('html_attributes', [
                'title' => 'Serienkalender für die KW ' . str_pad($start_of_week->week, 2, '0', STR_PAD_LEFT) . ' ' . $start_of_week->year,
                'description' => 'Kalender mit allen Ausstrahlungsdaten'
            ]);
    }
}
