<?php

namespace App\Apis\Tmdb\Shows\Seasons;

use App\Apis\Model;
use App\Apis\Tmdb\Http;
use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;

class Season extends Model
{
    public static function find(int $tv_id, int $season_number) : array
    {
        $url = '/tv/' . $tv_id . '/season/' . $season_number;

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

        if ($attributes_de['air_date']) {
            $first_aired_at = new Carbon($attributes_de['air_date']);
            $year = $first_aired_at->year;
        }
        elseif ($attributes_en['air_date']) {
            $first_aired_at = new Carbon($attributes_en['air_date']);
            $year = $first_aired_at->year;
        }
        else {
            $first_aired_at = null;
            $year = null;
        }

        $episodes = [];
        foreach ($attributes_en['episodes'] as $key => $episode) {
            if ($episode['episode_number'] == 0) {
                continue;
            }

            $episode['first_aired_at'] = $episode['air_date'];
            $episode['tmdb_id'] = $episode['id'];
            $episodes[$episode['episode_number']] = $episode;
        }

        foreach ($attributes_de['episodes'] as $key => $episode) {
            if ($episode['episode_number'] == 0) {
                continue;
            }

            if (! Arr::has($episodes, $episode['episode_number'])) {
                $episode['first_aired_at'] = $episode['air_date'];
                $episode['tmdb_id'] = $episode['id'];
                $episodes[$episode['episode_number']] = $episode;
            }

            if ($episode['name']) {
                $episodes[$episode['episode_number']]['name'] = $episode['name'];
            }

            if ($episode['overview']) {
                $episodes[$episode['episode_number']]['overview'] = $episode['overview'];
            }

            if ($episode['air_date']) {
                $episodes[$episode['episode_number']]['first_aired_at'] = $episode['air_date'];
            }

            if ($episode['still_path']) {
                $episodes[$episode['episode_number']]['still_path'] = $episode['still_path'];
            }
        }

        $attributes = $attributes_de;
        $attributes['overview'] = $attributes_de['overview'] ?: $attributes_en['overview'];
        $attributes['first_aired_at'] = $first_aired_at;
        $attributes['tmdb_id'] = $attributes_de['id'];
        $attributes['tvdb_id'] = $attributes_de['external_ids']['tvdb_id'];
        $attributes['episode_count'] = count($attributes_en['episodes']);
        $attributes['episodes'] = $episodes;

        return $attributes;
    }

    public static function changes(int $season_id)
    {
        $response = Http::get('/tv/season/' . $season_id . '/changes');

        return $response->json();
    }
}