<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Models\Genres\Genre;
use App\Models\Movies\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    protected $base_view_path = Movie::VIEW_PATH;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $models = Movie::whereNotNull('slug')
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
                'title' => 'Filme',
                'description' => 'Überblick über alle Filme auf serienguide.tv'
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Movies\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Movie $movie)
    {
        if ($request->wantsJson()) {
            return $movie;
        }

        $movie->load([
            'watched' => function ($query) {
                return $query->orderBy('watched_at', 'DESC');
            },
        ]);

        if (Auth::check()) {
            $movie->rating_by_user = $movie->ratingByUser(Auth::id());
        }

        $movie->load([
            'actors',
            'directors',
            'genres',
            'providers',
            'writers',
            'collection',
        ]);

        return view($this->base_view_path . '.show')
            ->with('model', $movie);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Movies\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movies\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Movies\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        //
    }
}
