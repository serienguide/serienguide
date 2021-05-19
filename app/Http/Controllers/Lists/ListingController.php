<?php

namespace App\Http\Controllers\Lists;

use App\Http\Controllers\Controller;
use App\Models\Lists\Listing;
use App\Models\User;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    protected $base_view_path = Listing::VIEW_PATH;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        if ($request->wantsJson()) {
            $query = $user->lists()
                ->orderBy('name', 'ASC')
                ->paginate();
        }

        return view($this->base_view_path . '.index')
            ->with('user', $user);
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
    public function store(Request $request, User $user)
    {
        $list = $user->lists()->create($request->validate([
            'name' => 'required|string',
        ]));

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
    public function show(Request $request, User $user, Listing $list)
    {
        $list->append([
            'path',
            'items_path',
        ]);

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
    public function edit(User $user, Listing $list)
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
    public function update(Request $request, User $user, Listing $list)
    {
        $this->authorize('update', $list);

        $attributes = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $list->update($attributes);

        if ($request->wantsJson()) {
            return $list;
        }

        return redirect($list->path)
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
    public function destroy(Request $request, User $user, Listing $list)
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
