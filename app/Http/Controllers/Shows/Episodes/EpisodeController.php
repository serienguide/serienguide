<?php

namespace App\Http\Controllers\Shows\Episodes;

use App\Http\Controllers\Controller;
use App\Models\Shows\Episodes\Episode;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    protected $base_view_path = Episode::VIEW_PATH;

    public function __construct()
    {
        $this->authorizeResource(Episode::class, 'episode');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            //
        }

        return view($this->base_view_path . '.index');
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
        $episode = Episode::create([
            //
        ]);

        if ($request->wantsJson()) {
            return $episode;
        }

        return redirect($episode->edit_path)
            ->with('status', [
                'type' => 'success',
                'text' => 'Datensatz erstellt.',
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shows\Episodes\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Episode $episode)
    {
        if ($request->wantsJson()) {
            return $episode;
        }

        return view($this->base_view_path . '.show')
            ->with('model', $episode);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shows\Episodes\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function edit(Episode $episode)
    {
        return view($this->base_view_path . '.edit')
            ->with('model', $episode);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shows\Episodes\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Episode $episode)
    {
        $attributes = $request->validate([
            //
        ]);

        $episode->update($attributes);

        if ($request->wantsJson()) {
            return $episode;
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
     * @param  \App\Models\Shows\Episodes\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Episode $episode)
    {
        if ($is_deletable = $episode->isDeletable()) {
            $episode->delete();
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

        if ($request->wantsJson()) {
            return [
                'deleted' => $is_deletable,
            ];
        }

        return redirect($episode->index_path)
            ->with('status', $status);
    }
}
