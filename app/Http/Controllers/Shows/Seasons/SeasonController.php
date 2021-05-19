<?php

namespace App\Http\Controllers\Shows\Seasons;

use App\Http\Controllers\Controller;
use App\Models\Shows\Seasons\Season;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    public function show(Request $request, Season $season)
    {
        $season->append([
            'progress'
        ]);

        return $season;
    }
}
