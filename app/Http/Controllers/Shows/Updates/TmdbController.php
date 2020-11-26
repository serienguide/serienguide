<?php

namespace App\Http\Controllers\Shows\Updates;

use App\Http\Controllers\Controller;
use App\Models\Shows\Show;
use Illuminate\Http\Request;

class TmdbController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shows\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Show $show)
    {
        echo 'Updating ' . $show->name;
        $show->updateFromTmdb();
        foreach ($show->seasons as $season) {
            $season->updateFromTmdb();
        }
        echo ' finished';
        $next = Show::where('id', '>', $show->id)->orderBy('id', 'ASC')->first();


        if (is_null($next)) {
            echo '<h1>Alles Fertig</h1>';
            return;
        }

        $next_url = route('shows.update.tmdb', [
            'show' => $next->id,
        ]);

        echo '<br /><br /><a href="' . $next_url . '">Weiter zu ' . $next->name . '</a>';
        echo '<META HTTP-EQUIV="refresh" content="1;URL=' . $next_url . '">';
    }
}
