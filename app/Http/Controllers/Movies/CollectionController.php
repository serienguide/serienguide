<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Models\Movies\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    protected $base_view_path = Collection::VIEW_PATH;

    public function __construct()
    {
        $this->authorizeResource(Collection::class, 'collection');
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
        $collection = Collection::create([
            //
        ]);

        if ($request->wantsJson()) {
            return $collection;
        }

        return redirect($collection->edit_path)
            ->with('status', [
                'type' => 'success',
                'text' => 'Datensatz erstellt.',
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Movies\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Collection $collection)
    {
        if ($request->wantsJson()) {
            return $collection;
        }

        return view($this->base_view_path . '.show')
            ->with('model', $collection);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Movies\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function edit(Collection $collection)
    {
        return view($this->base_view_path . '.edit')
            ->with('model', $collection);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movies\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Collection $collection)
    {
        $attributes = $request->validate([
            //
        ]);

        $collection->update($attributes);

        if ($request->wantsJson()) {
            return $collection;
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
     * @param  \App\Models\Movies\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Collection $collection)
    {
        if ($is_deletable = $collection->isDeletable()) {
            $collection->delete();
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

        return redirect($collection->index_path)
            ->with('status', $status);
    }
}
