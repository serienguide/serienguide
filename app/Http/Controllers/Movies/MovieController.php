<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Models\Movies\Movie;
use Illuminate\Http\Request;

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
                ->search($request->input('query'))
                ->orderBy('name', 'ASC')
                ->paginate(12);

            foreach ($models as $key => $model) {
                $model->toCard();
            }

            return $models;
        }

        return view($this->base_view_path . '.index')
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
