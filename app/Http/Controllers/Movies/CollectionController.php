<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Models\Movies\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    protected $base_view_path = Collection::VIEW_PATH;

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Movies\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Collection $collection)
    {
        $collection->load([
            'movies' => function ($query) {
                $query = $query->orderBy('year', 'ASC');

                if (auth()->check()) {
                    $query->withCount([
                        'watched' => function ($query) {
                            return $query->where('user_id', auth()->user()->id);
                        }
                    ]);
                }

                return $query;
            },
        ]);

        $collection->append([
            'last_watched',
            'progress',
        ]);

        if ($request->wantsJson()) {
            return $collection;
        }

        return view($this->base_view_path . '.show')
            ->with('model', $collection);
    }
}
