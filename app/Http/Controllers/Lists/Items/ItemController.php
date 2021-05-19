<?php

namespace App\Http\Controllers\Lists\Items;

use App\Http\Controllers\Controller;
use App\Models\Lists\Listing;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request, Listing $list)
    {
        $models = $list->items()
            ->with(['medium'])
            ->latest()
            ->paginate(12);

        foreach ($models as $model) {
            $model->medium->toCard();
        }

        return $models;
    }
}
