<?php

namespace App\Apis\Trakt;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http as BaseHttp;

class Http extends Factory
{
    protected static $access_token;

    public static function __callStatic($method, $parameters)
    {
        return self::builtClient()->$method(...$parameters);
    }

    public static function setAccessToken(string $access_token)
    {
        self::$access_token = $access_token;
    }

    protected static function builtClient()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'trakt-api-version' => 2,
            'trakt-api-key' => config('services.trakt.client_id'),
        ];

        if (self::$access_token) {
            $headers['Authorization'] = 'Bearer ' . self::$access_token;
        }

        return BaseHttp::withOptions([
            'headers' => $headers,
        ])->acceptJson()->baseUrl('https://api.trakt.tv/');
    }

    public static function get(string $url, array $query = [])
    {
        $response = self::builtClient()->get($url, $query);

        return self::handleResponse($response);
    }

    public static function post(string $url, array $data = [])
    {
        $response = self::builtClient()->post($url, $data);

        return self::handleResponse($response);
    }

    protected static function handleResponse($response)
    {
        if ($response->successful()) {
            return $response;
        }

        $status = $response->status();
        switch ($response->status()) {
            case 404:
                return $response;
                break;
            case 429:
                sleep($response->header('retry-after') ?: 1);
                return self::get($url, $query);
                break;

            default:
                $response->throw();
                break;
        }
    }
}