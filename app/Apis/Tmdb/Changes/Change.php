<?php

namespace App\Apis\Tmdb\Changes;

use App\Apis\Model;
use App\Apis\Tmdb\Http;
use App\Models\Movies\Movie;
use Carbon\Carbon;

class Change extends Model
{
    public static function movies() : void
    {
        return self::changes('movie', 'updateMovie');
    }

    protected static function changes(string $type, string $method) : void
    {
        $page = 1;
        $total_pages = 1;

        do {
            $response = Http::get('/' . $type . '/changes', [
                'page' => $page,
            ]);
            $data = $response->json();
            $total_pages = $data['total_pages'];

            foreach ($data['results'] as $key => $attributes) {
                $this->$method($attributes['id']);
            }

            $page++;
        }
        while ($page < $total_pages);
    }

    protected static function updateMovie(int $tmdb_id)
    {
        $model = Movie::where('tmdb_id', $tmdb_id)->first();
        if (is_null($model)) {
            return;
        }

        $model->updateFromTmdb($tmdb_id);
    }


}