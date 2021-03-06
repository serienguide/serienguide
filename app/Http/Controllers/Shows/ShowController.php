<?php

namespace App\Http\Controllers\Shows;

use App\Http\Controllers\Controller;
use App\Models\Genres\Genre;
use App\Models\Shows\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $models = Show::whereNotNull('slug')
                ->genre($request->input('genre_id'))
                ->search($request->input('query'))
                ->persons($request->input('person_ids'))
                ->orderBy('tmdb_popularity', 'DESC')
                ->paginate(12);

            foreach ($models as $key => $model) {
                $model->toCard();
            }

            return $models;
        }

        $filter_options = [
            'genre' => Genre::filterOptions(),
        ];

        return view($this->base_view_path . '.index')
            ->with('filter_options', $filter_options)
            ->with('html_attributes', [
                'title' => 'Serien',
                'description' => 'Überblick über alle Serien auf serienguide.tv'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shows\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Show $show)
    {
        if (Auth::check()) {
            $show->rating_by_user = $show->ratingByUser(Auth::id());
        }

        $show->load([
            'actors',
            'directors',
            'genres',
            'providers',
            'seasons.show',
            'writers',
        ]);

        $show->append([
            'last_watched',
            'last_aired_episodes',
            'next_episode_to_watch',
            'progress',
            'path',
        ]);

        foreach ($show->seasons as $season) {
            $season->append([
                'progress',
            ]);
        }

        if (! is_null($show->next_episode_to_watch)) {
            $show->next_episode_to_watch->toCard();
        }

        foreach ($show->last_aired_episodes as $episode) {
            $episode->toCard();
        }

        if ($request->wantsJson()) {
            return $show;
        }

        return view($this->base_view_path . '.show')
            ->with('model', $show);
    }
}
