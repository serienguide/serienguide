<?php

namespace App\Http\Controllers\Shows\Episodes;

use App\Http\Controllers\Controller;
use App\Models\Shows\Episodes\Episode;
use Illuminate\Http\Request;

class NextController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shows\Episodes\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Episode $episode)
    {
        return Episode::with([
            'season',
        ])->nextByAbsoluteNumber($episode->show_id, $episode->absolute_number)
        ->first()->toCard();
    }
}
