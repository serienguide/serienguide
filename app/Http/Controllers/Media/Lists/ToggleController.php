<?php

namespace App\Http\Controllers\Media\Lists;

use App\Http\Controllers\Controller;
use App\Models\Lists\Item;
use App\Models\Lists\Listing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ToggleController extends Controller
{
    public function update(Request $request, string $type, Model $model, Listing $list)
    {
        $attributes = [
            'list_id' => $list->id,
            'medium_type' => get_class($model),
            'medium_id' => $model->id,
        ];

        $item = Item::where($attributes)->first();
        if ($item) {
            $item->delete();
            return null;
        }

        return Item::create($attributes);
    }
}
