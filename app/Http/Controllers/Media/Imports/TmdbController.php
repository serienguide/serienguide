<?php

namespace App\Http\Controllers\Media\Imports;

use App\Http\Controllers\Controller;
use App\Support\Media;
use Illuminate\Http\Request;

class TmdbController extends Controller
{
    public function index(Request $request, string $media_type)
    {
        return view('media.imports.tmdb')
            ->with('media_type', $media_type)
            ->with('media_class_name', Media::className($media_type));
    }
}
