<?php

namespace App\Apis\Tmdb\Shows;

use App\Apis\Model;
use App\Apis\Tmdb\Http;
use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;

class Show extends Model
{
    public static function find(int $id) : array
    {
        $response_en = Http::get('/tv/' . $id, [
            'language' => 'en',
        ]);

        if ($response_en->failed()) {
            return [];
        }

        $response_de = Http::get('/tv/' . $id, [
            'language' => 'de',
            'append_to_response' => 'credits,external_ids,watch/providers,keywords',
        ]);

        if ($response_de->failed()) {
            return [];
        }

        $attributes_en = $response_en->json();
        $attributes_de = $response_de->json();

        if ($attributes_de['first_air_date']) {
            $first_aired_at = new Carbon($attributes_de['first_air_date']);
            $year = $first_aired_at->year;
        }
        elseif ($attributes_en['first_air_date']) {
            $first_aired_at = new Carbon($attributes_en['first_air_date']);
            $year = $first_aired_at->year;
        }
        else {
            $first_aired_at = null;
            $year = null;
        }

        if ($attributes_de['last_air_date']) {
            $last_aired_at = new Carbon($attributes_de['last_air_date']);
        }
        elseif ($attributes_en['last_air_date']) {
            $last_aired_at = new Carbon($attributes_en['last_air_date']);
        }
        else {
            $last_aired_at = null;
        }

        $seasons = [];
        foreach ($attributes_en['seasons'] as $key => $season) {
            if ($season['season_number'] == 0) {
                continue;
            }

            $season['first_aired_at'] = $season['air_date'];
            $season['tmdb_id'] = $season['id'];
            $seasons[$season['season_number']] = $season;
        }

        foreach ($attributes_de['seasons'] as $key => $season) {
            if ($season['season_number'] == 0) {
                continue;
            }

            if (! Arr::has($seasons, $season['season_number'])) {
                $season['first_aired_at'] = $season['air_date'];
                $season['tmdb_id'] = $season['id'];
                $seasons[$season['season_number']] = $season;
            }

            if ($season['overview']) {
                $seasons[$season['season_number']]['overview'] = $season['overview'];
            }

            if ($season['air_date']) {
                $seasons[$season['season_number']]['first_aired_at'] = $season['air_date'];
            }

            if ($season['poster_path']) {
                $seasons[$season['season_number']]['poster_path'] = $season['poster_path'];
            }
        }

        $attributes = $attributes_de;
        $attributes['keywords'] = $attributes_de['keywords']['results'];
        $attributes['providers'] = $attributes_de['watch/providers']['results']['DE'] ?? [];
        $attributes['name'] = $attributes_de['name'];
        $attributes['name_en'] = $attributes_en['name'];
        $attributes['overview'] = $attributes_de['overview'] ?: $attributes_en['overview'];
        $attributes['tagline'] = $attributes_de['tagline'] ?: $attributes_en['tagline'];
        $attributes['first_aired_at'] = $first_aired_at;
        $attributes['last_aired_at'] = $last_aired_at;
        $attributes['year'] = $year;
        $attributes['facebook'] = $attributes_de['external_ids']['facebook_id'];
        $attributes['instagram'] = $attributes_de['external_ids']['instagram_id'];
        $attributes['twitter'] = $attributes_de['external_ids']['twitter_id'];
        $attributes['tmdb_id'] = $attributes_de['id'];
        $attributes['tvdb_id'] = $attributes_de['external_ids']['tvdb_id'];
        $attributes['imdb_id'] = $attributes_de['external_ids']['imdb_id'];
        $attributes['seasons_count'] = max($attributes_en['number_of_seasons'], $attributes_en['number_of_seasons']);
        $attributes['episodes_count'] = max($attributes_en['number_of_episodes'], $attributes_de['number_of_episodes']);
        $attributes['runtime'] = (empty($attributes_de['episode_run_time']) ? 0 : round(array_sum($attributes_de['episode_run_time']) / count($attributes_de['episode_run_time'])));
        $attributes['seasons'] = $seasons;
        $attributes['tmdb_vote_count'] = $attributes_en['vote_count'];
        $attributes['tmdb_vote_average'] = $attributes_en['vote_average'];
        $attributes['tmdb_popularity'] = $attributes_de['popularity'];

        unset($attributes['vote_count'], $attributes['vote_average']);
        unset($attributes['watch/providers']);

        return $attributes;
    }

    public static function changes(int $id)
    {
        $response = Http::get('/tv/' . $id . '/changes');

        return $response->json();
    }

    public function getWatchProviders(string $language = 'de') : array
    {
        $response = Http::get('/tv/' . $this->id . '/watch/providers', [
            'language' => $language,
        ]);

        $body = $response->json();

        return $body['results'];
    }
}