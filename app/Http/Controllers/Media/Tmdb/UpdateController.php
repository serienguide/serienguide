<?php

namespace App\Http\Controllers\Media\Tmdb;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class UpdateController extends Controller
{
    public function show(Request $request, string $media_type, Model $model) {

        if ($model->updated_at->diffInHours() < 1) {
            return back()
                ->with('status', [
                    'type' => 'success',
                    'text' => 'Update wurde erst kürzlich ausgeführt.'
                ]);
        }

        Artisan::queue('apis:tmdb:' . $media_type . ':update', [
            'id' => $model->id,
            '--user' => auth()->user()->id,
        ]);

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => 'Update wird im Hintergrund durchgeführt.'
            ]);
    }
}
