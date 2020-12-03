<?php

namespace App\Apis\Tmdb\Changes;

use App\Apis\Model;
use App\Apis\Tmdb\Http;
use App\Apis\Tmdb\Person;
use App\Models\Movies\Movie;
use Carbon\Carbon;

class Change extends Model
{
    public static function movies() : void
    {
        self::changes('movie');
    }

    public static function people() : void
    {
        self::changes('person');
    }

    protected static function changes(string $type) : void
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
                $tmdb_id = $attributes['id'];
                if ($type == 'person') {
                    $model = Person::where('id', $tmdb_id)->first();
                }
                elseif ($type == 'movie') {
                    $model = Movie::where('tmdb_id', $tmdb_id)->first();
                }
                if (is_null($model)) {
                    continue;
                }
                $model->updateFromTmdb();
            }

            $page++;
        }
        while ($page < $total_pages);
    }

    public static function shows() : array
    {
        $page = 1;
        $total_pages = 1;
        $results = [];
        do {
            $response = Http::get('/tv/changes', [
                'page' => $page,
            ]);
            $data = $response->json();
            $total_pages = $data['total_pages'];

            foreach ($data['results'] as $key => $attributes) {
                $results[] = $attributes;
            }

            $page++;
        }
        while ($page < $total_pages);

        return $results;
    }
}