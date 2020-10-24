<?php

namespace App\Http\Controllers\Watched;

use App\Http\Controllers\Controller;
use App\Models\Movies\Movie;
use App\Models\Watched\Watched;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class WatchedController extends Controller
{
    protected $base_view_path = 'watchable.watched';

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Movies\Movie  $watchable
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $type, Movie $watchable)
    {
        if ($request->wantsJson()) {
            return $watchable->watched()
                ->latest()
                ->paginate();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Movies\Movie  $watchable
     * @return \Illuminate\Http\Response
     */
    public function create(string $type, Movie $watchable)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movies\Movie  $watchable
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, string $type, Movie $watchable)
    {
        $attributes = $request->validate([
            'watched_at' => 'sometimes|date',
        ]);

        $watched = $watchable->watchedBy(auth()->user(), $attributes);

        return $watched;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Movies\Movie  $watchable
     * @param  \App\Models\Watched\Watched  $watched
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $type, Movie $watchable, Watched $watched)
    {
        $this->authorize('view', $watched);

        if ($request->wantsJson()) {
            return $watched;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Movies\Movie  $watchable
     * @param  \App\Models\Watched\Watched  $watched
     * @return \Illuminate\Http\Response
     */
    public function edit(string $type, Movie $watchable, Watched $watched)
    {
        $this->authorize('update', $watched);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movies\Movie  $watchable
     * @param  \App\Models\Watched\Watched  $watched
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $type, Movie $watchable, Watched $watched)
    {
        $this->authorize('update', $watched);

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
     * @param  \App\Models\Movies\Movie  $watchable
     * @param  \App\Models\Watched\Watched  $watched
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $type, Movie $watchable, Watched $watched)
    {
        $this->authorize('delete', $watched);

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

        return redirect(route($this->base_view_path . '.index', ['type' => 'movies', 'watchable' => $watchable->id]))
            ->with('status', $status);
    }
}
