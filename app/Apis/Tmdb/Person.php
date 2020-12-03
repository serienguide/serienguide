<?php

namespace App\Apis\Tmdb;

use App\Apis\Model;
use App\Apis\Tmdb\Http;
use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;

class Person extends Model
{
    public static function find(int $id) : array
    {
        $url = '/person/' . $id;

        $response_en = Http::get($url , [
            'language' => 'en',
        ]);

        if ($response_en->failed()) {
            return [];
        }

        $response_de = Http::get($url, [
            'language' => 'de',
            'append_to_response' => 'external_ids',
        ]);

        if ($response_de->failed()) {
            return [];
        }

        $attributes_en = $response_en->json();
        $attributes_de = $response_de->json();

        $attributes = $attributes_de;
        $attributes['biography'] = $attributes_de['biography'] ?: $attributes_en['biography'];
        $attributes['profile_path'] = $attributes_de['profile_path'] ?: $attributes_en['profile_path'];
        $attributes['tmdb_id'] = $attributes_de['id'];
        $attributes['imdb_id'] = $attributes_de['external_ids']['imdb_id'];
        $attributes['facebook'] = $attributes_de['external_ids']['facebook_id'];
        $attributes['instagram'] = $attributes_de['external_ids']['instagram_id'];
        $attributes['twitter'] = $attributes_de['external_ids']['twitter_id'];
        // $attributes['tmdb_vote_count'] = $attributes_en['vote_count'];
        // $attributes['tmdb_vote_average'] = $attributes_en['vote_average'];
        $attributes['tmdb_popularity'] = $attributes_de['popularity'];

        return $attributes;
    }

    public function changes(int $id)
    {
        $response = Http::get('/person/' . $id . '/changes');
    }
}