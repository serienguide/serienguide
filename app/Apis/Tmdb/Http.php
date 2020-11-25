<?php

namespace App\Apis\Tmdb;

use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\Http as BaseHttp;

class Http extends Factory
{
    public static function __callStatic($method, $parameters)
    {
        return self::builtClient()->$method(...$parameters);
    }

    protected static function builtClient()
    {
        return BaseHttp::withOptions([
            'query' => [
                'api_key' => config('services.tmdb.token')
            ],
        ])->acceptJson()->baseUrl('https://api.themoviedb.org/3');
    }

    public static function get(string $url, array $query = [])
    {
        $response = self::builtClient()->get($url, $query);

        if ($response->successful()) {
            return $response;
        }

        $status = $response->status();
        switch ($response->status()) {
            case 429:
                sleep($response->header('retry-after') ?: 1);
                return self::get($url, $query);
                break;

            default:
                # code...
                break;
        }

    }
}