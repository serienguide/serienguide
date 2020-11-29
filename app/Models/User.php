<?php

namespace App\Models;

use App\Models\Lists\Listing;
use App\Traits\HasSlug;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function lists() : HasMany
    {
        return $this->hasMany(Listing::class, 'user_id');
    }
}
