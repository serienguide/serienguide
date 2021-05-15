<?php

namespace App\Http\Controllers\Watched;

use App\Http\Controllers\Controller;
use App\Models\Movies\Movie;
use App\Models\Watched\Watched;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class WatchedController extends Controller
{
    protected $base_view_path = Watched::VIEW_PATH;

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Movies\Movie  $model
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $type, Model $model)
    {
        if ($request->wantsJson()) {
            return $model->watched()
                ->latest('watched_at')
                ->get();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Movies\Movie  $model
     * @return \Illuminate\Http\Response
     */
    public function create(string $type, Model $model)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movies\Movie  $model
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, string $type, Model $model)
    {
        $attributes = $request->validate([
            'watched_at_formatted' => 'sometimes|date_format:"d.m.Y H:i"',
        ]);

        $watched = $model->watchedBy(auth()->user(), $attributes);

        return [
            'watched' => $watched,
            'progress' => $model->progress,
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Movies\Movie  $model
     * @param  \App\Models\Watched\Watched  $watched
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $type, Model $model, Watched $watched)
    {
        $this->authorize('view', $watched);

        if ($request->wantsJson()) {
            return $watched;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Movies\Movie  $model
     * @param  \App\Models\Watched\Watched  $watched
     * @return \Illuminate\Http\Response
     */
    public function edit(string $type, Model $model, Watched $watched)
    {
        $this->authorize('update', $watched);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movies\Movie  $model
     * @param  \App\Models\Watched\Watched  $watched
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $type, Model $model, Watched $watched)
    {
        $this->authorize('update', $watched);

        $attributes = $request->validate([
            'watched_at_formatted' => 'required|date_format:"d.m.Y H:i"',
        ]);

        $watched->update($attributes);

        if ($request->wantsJson()) {
            return [
                'watched' => $watched->load(['watchable']),
                'progress' => $model->progress,
            ];
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
     * @param  \App\Models\Movies\Movie  $model
     * @param  \App\Models\Watched\Watched  $watched
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $type, Model $model, Watched $watched)
    {
        $this->authorize('delete', $watched);

        if ($is_deletable = $watched->isDeletable()) {
            $watched->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $is_deletable,
                'progress' => $model->progress,
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

        return redirect($model->path)
            ->with('status', $status);
    }
}
