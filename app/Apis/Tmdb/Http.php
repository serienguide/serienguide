<?php

namespace App\Apis\Tmdb;

use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\Http as BaseHttp;

class Http extends Factory
{
    public static function __callStatic($method, $parameters)
    {
        return BaseHttp::withOptions([
            'query' => [
                'api_key' => config('services.tmdb.token')
            ],
        ])->acceptJson()->baseUrl('https://api.themoviedb.org/3')->$method(...$parameters);
    }
}