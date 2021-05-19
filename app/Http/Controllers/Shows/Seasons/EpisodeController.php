<?php

namespace App\Http\Controllers\Shows\Seasons;

use App\Http\Controllers\Controller;
use App\Models\Shows\Seasons\Season;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    public function index(Request $request, Season $season)
    {
        $episodes = $season->episodes()
            ->with([
                'show',
                'season',
            ])
            ->orderBy('episode_number', 'ASC')
            ->get();

        foreach ($episodes as $episode) {
            $episode->toCard();
        }

        return $episodes;
    }
}
