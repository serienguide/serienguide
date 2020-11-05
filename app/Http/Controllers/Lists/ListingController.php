<?php

namespace App\Http\Controllers\Lists;

use App\Http\Controllers\Controller;
use App\Models\Lists\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    protected $base_view_path = Listing::VIEW_PATH;

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
        return 'test';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $list = Listing::create([
            //
        ]);

        if ($request->wantsJson()) {
            return $list;
        }

        return redirect($list->edit_path)
            ->with('status', [
                'type' => 'success',
                'text' => 'Datensatz erstellt.',
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lists\Listing  $list
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Listing $list)
    {
        if ($request->wantsJson()) {
            return $list;
        }

        return view($this->base_view_path . '.show')
            ->with('model', $list);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lists\Listing  $list
     * @return \Illuminate\Http\Response
     */
    public function edit(Listing $list)
    {
        $this->authorize('update', $list);

        return view($this->base_view_path . '.edit')
            ->with('model', $list);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lists\Listing  $list
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Listing $list)
    {
        $this->authorize('update', $list);

        $attributes = $request->validate([
            //
        ]);

        $list->update($attributes);

        if ($request->wantsJson()) {
            return $list;
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
     * @param  \App\Models\Lists\Listing  $list
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Listing $list)
    {
        $this->authorize('delete', $list);

        if ($is_deletable = $list->isDeletable()) {
            $list->delete();
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

        return redirect($list->index_path)
            ->with('status', $status);
    }
}
