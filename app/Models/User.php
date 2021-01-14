<?php

namespace App\Models;

use App\Models\Auth\OauthProvider;
use App\Models\Lists\Listing;
use App\Models\Ratings\Rating;
use App\Models\Watched\Watched;
use App\Traits\HasSlug;
use App\Traits\Users\Followable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Followable;
    use HasProfilePhoto;
    use HasSlug;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $dates = [
        'last_login_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function setSlug() : void
    {
        if (! $this->isDirty('name')) {
            return;
        }

        $slug = Str::slug($this->name, '-', 'de');
        if (self::where('id', '!=', $this->id)->slug($slug)->exists()) {
            $slug .= '-' . (self::where('name', $this->name)->count());
        }
        $this->attributes['slug'] = $slug;
    }

    public function getProfilePathAttribute() : string
    {
        return route('users.profiles.show', [
            'user' => $this->id,
        ]);
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        return 'https://www.gravatar.com/avatar/' . md5($this->email);
    }

    public function lists() : HasMany
    {
        return $this->hasMany(Listing::class, 'user_id');
    }

    public function watchlist() : HasOne
    {
        return $this->hasOne(Listing::class, 'user_id')->where('type', 'watchlist')->take(1);
    }

    public function currently_watching_list() : HasOne
    {
        return $this->hasOne(Listing::class, 'user_id')->where('type', 'currently_watching')->take(1);
    }

    public function oauth_providers() : HasMany
    {
        return $this->hasMany(OauthProvider::class, 'user_id');
    }

    public function ratings() : HasMany
    {
        return $this->hasMany(Rating::class, 'user_id');
    }

    public function watched() : HasMany
    {
        return $this->hasMany(Watched::class, 'user_id');
    }

    public function last_watched() : HasOne
    {
        return $this->hasOne(Watched::class, 'user_id')->latest()->take(1)->whereHas('watchable');
    }
}
