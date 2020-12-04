<?php

namespace App\Models\Auth;

use App\Apis\Trakt\Trakt;
use App\Traits\BelongsToUser;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class OauthProvider extends Model
{
    use BelongsToUser,
        HasFactory,
        HasModelPath;

    const ROUTE_NAME = 'providers';
    const VIEW_PATH = 'auth.providers';

    protected $appends = [
        //
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'expires_at',
        'synced_at',
    ];

    protected $fillable = [
        'user_id',
        'provider_type',
        'provider_id',
        'token',
        'token_secret',
        'refresh_token',
        'expires_in',
        'expires_at',
        'synced_at',
    ];

    public function isDeletable() : bool
    {
        return true;
    }

    public function refresh()
    {
        switch ($this->provider_type) {
            case 'trakt':
                return $this->refreshTraktToken();
                break;

            default:
                return false;
                break;
        }
    }

    protected function refreshTraktToken() : bool
    {
        $token = Trakt::refreshToken($this->refresh_token);
        if ($token == false || (is_array($token) && Arr::has($token, 'error'))) {
            $this->delete();
            return false;
        }

        $this->update([
            'token' => $token['access_token'],
            'refresh_token' => $token['refresh_token'],
        ]);

        return true;
    }

    public function getRedirectRouteAttribute()
    {
        return route('login.provider.redirect', [
            'provider' => $this->provider_type,
        ]);
    }

    public function getCallbackRouteAttribute()
    {
        return route('login.provider.callback', [
            'provider' => $this->provider_type,
        ]);
    }
}