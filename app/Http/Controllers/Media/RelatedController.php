<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RelatedController extends Controller
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
            $collection = $model->related();

            foreach ($collection as $related) {
                $related->toCard();
            }

            return $collection;
        }
    }
}
