<?php

namespace App\Apis\Tmdb;

use App\Apis\Model;
use App\Apis\Tmdb\Http;
use App\Models\Movies\Movie;
use App\Models\People\Person;
use App\Models\Shows\Show;
use Carbon\Carbon;

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
                    $attributes['first_air_date'] = $attributes['release_date'];
                }
                $results[] = $attributes;
                $trending++;
            }
            $page++;
        }
        while ($page < $total_pages);

        return $results;
    }
}