<?php

namespace App\Http\Controllers\Shows\Seasons\Updates;

use App\Http\Controllers\Controller;
use App\Models\Shows\Seasons\Season;
use Illuminate\Http\Request;

class TmdbController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shows\Seasons\Season  $season
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Season $season)
    {
        echo 'Updating Season ' . $season->id;
        $season->updateFromTmdb();
        echo ' finished';
        $next = Season::where('id', '>', $season->id)->orderBy('id', 'ASC')->first();

        if (is_null($next)) {
            echo '<h1>Alles Fertig</h1>';
            return;
        }

        $next_url = route('seasons.update.tmdb', [
            'season' => $next->id,
        ]);

        echo '<br /><br /><a href="' . $next_url . '">Weiter zu ' . $next->id . '</a>';
        echo '<META HTTP-EQUIV="refresh" content="1;URL=' . $next_url . '">';
    }
}
