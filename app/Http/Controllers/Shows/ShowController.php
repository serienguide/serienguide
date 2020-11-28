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
     * @param  \App\Models\Shows\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Show $show)
    {
        $show->load([
            'seasons',
            'genres',
            'providers',
        ]);

        if ($request->wantsJson()) {
            return $show;
        }

        return view($this->base_view_path . '.show')
            ->with('model', $show);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shows\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function edit(Show $show)
    {
        return view($this->base_view_path . '.edit')
            ->with('model', $show);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shows\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Show $show)
    {
        $attributes = $request->validate([
            //
        ]);

        $show->update($attributes);

        if ($request->wantsJson()) {
            return $show;
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
     * @param  \App\Models\Shows\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Show $show)
    {
        if ($is_deletable = $show->isDeletable()) {
            $show->delete();
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

        return redirect($show->index_path)
            ->with('status', $status);
    }
}
