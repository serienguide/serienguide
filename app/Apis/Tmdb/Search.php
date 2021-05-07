<?php

namespace App\Apis\Tmdb;

use App\Apis\Model;
use App\Apis\Tmdb\Http;
use App\Models\Movies\Movie;
use App\Models\People\Person;
use App\Models\Shows\Show;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Search
{
    public static function movies(string $query) : array
    {
        return self::get('movie', $query);
    }

    public static function shows(string $query) : array
    {
        return self::get('tv', $query);
    }

    public static function people(string $query) : array
    {
        return self::get('person', $query);
    }

    public static function get(string $type, string $query) : array
    {
        $page = 1;
        $total_pages = 1;
        $trending = 1;
        $results = [];
        do {
            $response = Http::get('/search/' . $type, [
                'page' => $page,
                'query' => $query,
            ]);
            $data = $response->json();
            $total_pages = 1; // $data['total_pages'];
            foreach ($data['results'] as $key => $attributes) {
                if ($type == 'movie') {
                    $attributes['name'] = $attributes['title'];
                    $attributes['first_air_date'] = Arr::get($attributes, 'release_date');
                }
                $attributes['poster_path_formatted'] = $attributes['poster_path'] ? 'https://image.tmdb.org/t/p/w300_and_h450_bestv2' . $attributes['poster_path'] : Storage::disk('s3')->url('no/680x1000.png');
                $attributes['first_air_date_formatted'] = (((Arr::has($attributes, 'first_air_date') && $attributes['first_air_date']) ? (new \Carbon\Carbon($attributes['first_air_date']))->format('d.m.Y') : ''));
                $results[] = $attributes;
                $trending++;
            }
            $page++;
        }
        while ($page < $total_pages);

        return $results;
    }
}