<?php

namespace App\Apis\Tmdb\Shows\Episodes;

use App\Apis\Model;
use App\Apis\Tmdb\Http;
use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;

class Episode extends Model
{
    public static function find(int $tv_id, int $season_number, int $episode_number) : self
    {
        $url = '/tv/' . $tv_id . '/season/' . $season_number . '/episode/' . $episode_number;

        $response_en = Http::get($url , [
            'language' => 'en',
        ]);
        $response_de = Http::get($url, [
            'language' => 'de',
            'append_to_response' => 'external_ids',
        ]);

        $attributes_en = $response_en->json();
        $attributes_de = $response_de->json();

        $attributes = $attributes_de;
        $attributes['name'] = $attributes_de['name'];
        $attributes['name_en'] = $attributes_en['name'];
        $attributes['overview'] = $attributes_de['overview'] ?: $attributes_en['overview'];
        $attributes['first_aired_at'] = $attributes_de['air_date'] ?: $attributes_en['air_date'];
        $attributes['still_path'] = $attributes_de['still_path'] ?: $attributes_en['still_path'];
        $attributes['tmdb_id'] = $attributes_de['id'];
        $attributes['tvdb_id'] = $attributes_de['external_ids']['tvdb_id'];
        $attributes['imdb_id'] = $attributes_de['external_ids']['imdb_id'];

        return new static($attributes);
    }

    public function changed(int $episode_id)
    {
        $response = Http::get('/tv/episode/' . $episode_id . '/changes', [
            'page' => 1,
        ]);
    }
}