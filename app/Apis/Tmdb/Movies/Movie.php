<?php

namespace App\Apis\Tmdb\Movies;

use App\Apis\Model;
use App\Apis\Tmdb\Http;
use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;

class Movie extends Model
{
    protected $base_url = 'https://api.themoviedb.org/3/';

    // movie/76341?api_key=<<api_key>>

    public static function find(int $id) : self
    {
        $response_en = Http::get('/movie/' . $id, [
            'language' => 'en',
        ]);
        $response_de = Http::get('/movie/' . $id, [
            'language' => 'de',
        ]);

        $attributes_en = $response_en->json();
        $attributes_de = $response_de->json();

        if ($attributes_de['release_date']) {
            $released_at = new Carbon($attributes_de['release_date']);
            $year = $released_at->year;
        }
        elseif ($attributes_en['release_date']) {
            $released_at = new Carbon($attributes_en['release_date']);
            $year = $released_at->year;
        }
        else {
            $released_at = null;
            $year = null;
        }

        return new static([
            'budget' => $attributes_de['budget'],
            'homepage' => $attributes_de['homepage'],
            'overview' => $attributes_de['overview'] ?: $attributes_en['overview'],
            'released_at' => $released_at,
            'revenue' => $attributes_de['revenue'],
            'runtime' => $attributes_de['runtime'],
            'status' => $attributes_de['status'],
            'tagline' => $attributes_de['tagline'] ?: $attributes_en['tagline'],
            'title' => $attributes_de['title'],
            'title_en' => $attributes_en['title'],
            'year' => $year,
        ]);
    }
}