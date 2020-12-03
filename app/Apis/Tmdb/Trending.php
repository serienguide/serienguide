<?php

namespace App\Apis\Tmdb;

use App\Apis\Model;
use App\Apis\Tmdb\Http;
use App\Models\Movies\Movie;
use App\Models\People\Person;
use App\Models\Shows\Show;
use Carbon\Carbon;

class Trending extends Model
{
    public static function movies() : void
    {
        self::get('movie', Movie::class);
    }

    public static function people() : void
    {
        self::get('person', Person::class);
    }

    public static function shows() : void
    {
        self::get('tv', Show::class);
    }

    public static function get(string $type, string $class_name) : void
    {
        $page = 1;
        $total_pages = 1;
        $trending = 1;
        do {
            $response = Http::get('/trending/' . $type . '/day', [
                'page' => $page,
            ]);
            $data = $response->json();
            $total_pages = $data['total_pages'];
            foreach ($data['results'] as $key => $attributes) {
                $model = $class_name::where('tmdb_id', $attributes['id'])->first();
                if (is_null($model)) {
                    continue;
                }

                $model->update([
                    'tmdb_trending' => $trending,
                    'tmdb_popularity' => $attributes['popularity'],
                ]);
                $trending++;
            }
            $page++;
        }
        while ($page < $total_pages);
    }
}