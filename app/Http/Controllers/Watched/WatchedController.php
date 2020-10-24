<?php

namespace App\Http\Controllers\Watched;

use App\Http\Controllers\Controller;
use App\Models\Movies\Movie;
use App\Models\Watched\Watched;
use Illuminate\Http\Request;

class WatchedController extends Controller
{
    protected $base_view_path = 'movies.watched';

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Movies\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function index(Movie $movie)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Movies\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function create(Movie $movie)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movies\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Movie $movie)
    {
        $watched = $movie->watched()->create([
            'user_id' => auth()->id(),
            'watched_at' => now(),
        ]);

        return $watched;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Movies\Movie  $movie
     * @param  \App\Models\Watched\Watched  $watched
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Movie $movie, Watched $watched)
    {
        if ($request->wantsJson()) {
            return $watched;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Movies\Movie  $movie
     * @param  \App\Models\Watched\Watched  $watched
     * @return \Illuminate\Http\Response
     */
    public function edit(Movie $movie, Watched $watched)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movies\Movie  $movie
     * @param  \App\Models\Watched\Watched  $watched
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie, Watched $watched)
    {
        $attributes = $request->validate([
            'watched_at' => 'required|date',
        ]);

        $watched->update($attributes);

        if ($request->wantsJson()) {
            return $watched->load([
                'watchable',
            ]);
        }

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => 'Datensatz gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Movies\Movie  $movie
     * @param  \App\Models\Watched\Watched  $watched
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Movie $movie, Watched $watched)
    {
        if ($is_deletable = $watched->isDeletable()) {
            $watched->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $is_deletable,
            ];
        }

        if ($is_deletable) {
            $status = [
                'type' => 'success',
                'text' => 'Datensatz gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => 'Datensatz kann nicht gelöscht werden.',
            ];
        }

        return redirect(route($this->base_view_path . '.index', ['movie' => $movie->id]))
            ->with('status', $status);
    }
}
