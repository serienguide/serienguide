<?php

namespace App\Models\Auth;

use App\Traits\BelongsToUser;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OauthProvider extends Model
{
    use BelongsToUser,
        HasFactory;

    const ROUTE_NAME = '';
    const VIEW_PATH = '';

    protected $appends = [
        //
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'expires_at',
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
    ];

    public function isDeletable() : bool
    {
        return true;
    }
}