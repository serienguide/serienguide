<?php

namespace App\Http\Controllers\Media\Imports;

use App\Http\Controllers\Controller;
use App\Support\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class TmdbController extends Controller
{
    public function index(Request $request, string $media_type)
    {
        if ($request->wantsJson()) {

            if (! $request->input('query')) {
                return [];
            }

            return \App\Apis\Tmdb\Search::$media_type($request->input('query'));
        }

        return view('media.imports.tmdb')
            ->with('media_type', $media_type)
            ->with('media_class_name', Media::className($media_type));
    }

    public function store(Request $request, string $media_type) {

        $class_name = Media::className($media_type);

        $attributes = $request->validate([
            'id' => 'required|numeric',
            'name' => 'required|string',
        ]);

        $model = $class_name::firstOrCreate([
            'tmdb_id' => $attributes['id'],
        ], [
            'name' => $attributes['name'],
            'name_en' => $attributes['name'],
        ]);

        if (! $model->wasRecentlyCreated) {
            return [
                'is_created' => false,
                'flash' => [
                    'type' => 'success',
                    'text' => $class_name::label(1) . ' ist bereits vorhanden. Du wirst weitergeleitet.',
                ],
                'path' => $model->path,
            ];
        }

        Artisan::queue('apis:tmdb:' . $media_type . ':update', [
            'id' => $model->id,
            '--user' => auth()->user()->id,
        ]);

        return [
            'is_created' => true,
            'flash' => [
                'type' => 'success',
                'text' => $class_name::label(1) . ' wurde angelegt und ein Update im Hintergrund angestoßen.',
            ],
        ];

    }
}
