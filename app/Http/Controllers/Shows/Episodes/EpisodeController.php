<?php

namespace App\Http\Controllers\Shows\Episodes;

use App\Http\Controllers\Controller;
use App\Models\Shows\Episodes\Episode;
use App\Models\Shows\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EpisodeController extends Controller
{
    protected $base_view_path = Episode::VIEW_PATH;

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shows\Episodes\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Show $show, int $season_number, int $episode_number)
    {
        $season = $show->seasons()->where('season_number', $season_number)->firstOrFail();
        $episode = $season->episodes()->where('episode_number', $episode_number)->firstOrFail();
        $episode->show = $show;
        $episode->season = $season;

        if (Auth::check()) {
            $episode->rating_by_user = $episode->ratingByUser(Auth::id());
        }

        if ($request->wantsJson()) {
            return $episode;
        }

        return view($this->base_view_path . '.show')
            ->with('model', $episode)
            ->with('next_episode', $episode->next())
            ->with('previous_episode', $episode->previous());
    }
}
