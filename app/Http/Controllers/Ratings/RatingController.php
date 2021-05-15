<?php

namespace App\Http\Controllers\Ratings;

use App\Http\Controllers\Controller;
use App\Models\Ratings\Rating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    protected $base_view_path = Rating::VIEW_PATH;

    public function __construct()
    {
        $this->authorizeResource(Rating::class, 'rating');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $type, Model $model)
    {
        if ($request->wantsJson()) {
            return $model->ratings()
                ->latest()
                ->paginate();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, string $type, Model $model)
    {
        $attributes = $request->validate([
            'rating' => 'required|integer|between:0,10',
        ]);

        return $model->rateBy(auth()->user(), $attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ratings\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $type, Model $model, Rating $rating)
    {
        if ($request->wantsJson()) {
            return $rating;
        }

        return view($this->base_view_path . '.show')
            ->with('model', $rating);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ratings\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, string $type, Model $model, Rating $rating)
    {
        return view($this->base_view_path . '.edit')
            ->with('model', $rating);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ratings\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $type, Model $model, Rating $rating)
    {
        $attributes = $request->validate([
            'rating' => 'required|integer|between:1,10',
        ]);

        $rating->update($attributes);

        if ($request->wantsJson()) {
            return $rating;
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
     * @param  \App\Models\Ratings\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $type, Model $model, Rating $rating)
    {
        if ($is_deletable = $rating->isDeletable()) {
            $rating->delete();
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

        return redirect($rating->index_path)
            ->with('status', $status);
    }
}
