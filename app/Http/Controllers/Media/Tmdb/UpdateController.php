<?php

namespace App\Http\Controllers\Media\Tmdb;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class UpdateController extends Controller
{
    public function show(Request $request, string $medium_type, Model $model) {

        if ($model->updated_at->diffInHours() < 1) {
            return back();
        }

        Artisan::queue('apis:tmdb:' . $medium_type . ':update', [
            'id' => $model->id,
        ]);

        return back();
    }
}