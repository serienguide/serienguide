<?php

namespace App\Apis\Tmdb\Movies;

use App\Apis\Model;
use App\Apis\Tmdb\Http;
use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;

class Movie extends Model
{
    protected $base_url = 'https://api.themoviedb.org/3/';

    public static function find(int $id) : array
    {
        $response_en = Http::get('/movie/' . $id, [
            'language' => 'en',
        ]);

        if ($response_en->failed()) {
            return [];
        }

        $response_de = Http::get('/movie/' . $id, [
            'language' => 'de',
            'append_to_response' => 'credits,external_ids,watch/providers,keywords',
        ]);

        if ($response_de->failed()) {
            return [];
        }

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

        $attributes = $attributes_de;
        $attributes['keywords'] = $attributes_de['keywords']['keywords'] ?? [];
        $attributes['providers'] = $attributes_de['watch/providers']['results']['DE'] ?? [];
        $attributes['name'] = $attributes_de['title'];
        $attributes['name_en'] = $attributes_en['title'];
        $attributes['overview'] = $attributes_de['overview'] ?: $attributes_en['overview'];
        $attributes['tagline'] = $attributes_de['tagline'] ?: $attributes_en['tagline'];
        $attributes['released_at'] = $released_at;
        $attributes['year'] = $year;
        $attributes['facebook'] = Arr::get($attributes_de, 'external_ids.facebook_id', null);
        $attributes['instagram'] = Arr::get($attributes_de, 'external_ids.instagram_id', null);
        $attributes['twitter'] = Arr::get($attributes_de, 'external_ids.twitter_id', null);
        $attributes['imdb_id'] = Arr::get($attributes_de, 'external_ids.imdb_id', null);
        $attributes['tmdb_vote_count'] = $attributes_en['vote_count'];
        $attributes['tmdb_vote_average'] = $attributes_en['vote_average'];
        $attributes['tmdb_popularity'] = $attributes_de['popularity'];

        unset($attributes['vote_count'], $attributes['vote_average']);

        return $attributes;
    }

    public function changed()
    {
        $response = Http::get('/movie/' . $id . '/changes', [
            'page' => 1,
        ]);
    }

    public function getWatchProviders(string $language = 'de') : array
    {
        $response = Http::get('/movie/' . $this->id . '/watch/providers', [
            'language' => $language,
        ]);

        $body = $response->json();

        return $body['results'];
    }
}