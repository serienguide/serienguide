<?php

namespace App\Apis\Glotz;

use Illuminate\Support\Facades\Http;

class Show
{
    const BASE_URL = 'https://www.glotz.info/v2/';

    public static function find(int $tvdb_id)
    {
        $response = Http::get(self::BASE_URL . 'show/summary/' . config('services.glotz.token') . '/' . $tvdb_id . '/extended')->throw();

        return $response->json();
    }

    public static function updated()
    {
        $response = Http::get(self::BASE_URL . 'shows/updated/' . config('services.glotz.token') . '/' . now()->subHours(24)->format('U'))->throw();

        return $response->json();
    }
}