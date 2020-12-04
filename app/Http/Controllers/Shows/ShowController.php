<?php

namespace App\Http\Controllers\Shows;

use App\Http\Controllers\Controller;
use App\Models\Shows\Show;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    protected $base_view_path = Show::VIEW_PATH;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Show::orderBy('title', 'ASC')
                ->paginate();
        }

        return view($this->base_view_path . '.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shows\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Show $show)
    {
        if ($request->wantsJson()) {
            return $show;
        }

        $show->load([
            'actors',
            'directors',
            'genres',
            'providers',
            'seasons',
            'writers',
        ]);

        $show->append([
            'last_watched',
            'last_aired_episodes',
            'next_episode_to_watch',
            'progress',
        ]);

        return view($this->base_view_path . '.show')
            ->with('model', $show);
    }
}
