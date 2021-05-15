<?php

namespace App\Http\Controllers\Media\Lists;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Movies\Movie  $model
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $type, Model $model)
    {
        if ($request->wantsJson()) {
            $lists = auth()->user()->lists()
                ->with([
                    'items' => function ($query) use ($model) {
                        return $query->where([
                            'medium_type' => get_class($model),
                            'medium_id' => $model->id,
                        ]);
                    },
                ])
                ->orderBy(DB::raw('IF(lists.type IS NULL, 0, 1)'), 'DESC')
                ->orderBy('name', 'ASC')
                ->get();

            foreach ($lists as $key => $list) {
                $list->toggle_path = route('media.lists.toggle.update', [
                    'media_type' => $model->media_type,
                    'model' => $model->id,
                    'list' => $list->id,
                ]);
            }

            return $lists;
        }
    }
}
