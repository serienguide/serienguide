<?php

namespace App\Apis\Trakt;

use App\Apis\Model;
use App\Apis\Trakt\Http;
use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;

class Trakt extends Model
{
    public static function lastActivities()
    {
        $response = Http::get('sync/last_activities');

        return $response->json();
    }

    public static function watched(string $type) : array {

        $data = [];

        $page = 1;
        $total_pages = 1;
        do {
            $response = Http::get('sync/watched/' . $type, [
                'page' => $page,
            ]);

            $total_pages = $response->header('X-Pagination-Page-Count') ?: 1;

            foreach ($response->json() as $value) {
                $data[] = $value;
            }

            $page++;
        }
        while ($page < $total_pages);

        return $data;

    }

    public static function watchedHistory(string $type, ?Carbon $start_at, int $id = 0)
    {
        $parameters['page'] = 1;
        if ($start_at) {
            $parameters['start_at'] = $start_at->format(\DATETIME::ISO8601);
        }

        $response = Http::get('sync/history/' . $type . ($id ? '/' . $id : ''), $parameters);

        return $response->json();
    }

    public static function searchByTvdbId(int $tvdb_id)
    {
        $response = Http::get('/search/tvdb/' . $tvdb_id, [
            'type' => 'show',
        ]);

        return $response->json();
    }

    public static function setAccessToken(string $access_token)
    {
        Http::setAccessToken($access_token);
    }

    public static function refreshToken(string $refresh_token)
    {
        $response = Http::post('oauth/token', [
            'refresh_token' => $refresh_token,
            'client_id'     => config('services.trakt.client_id'),
            'client_secret' => config('services.trakt.client_secret'),
            'redirect_uri'  => url(config('services.trakt.redirect')),
            'grant_type'    => 'refresh_token',
        ]);

        return $response->json();
    }
}