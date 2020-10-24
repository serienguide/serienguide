<?php

namespace App\Models\Movies;

use App\Models\User;
use App\Models\Watched\Watched;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;

class Movie extends Model
{
    use HasFactory;

    protected $appends = [
        //
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        //
    ];

    public function isDeletable() : bool
    {
        return true;
    }

    protected function getBaseRouteAttribute() : string
    {
        return 'movies';
    }

    public function watchedBy(User $user, array $attributes = []) : Watched
    {
        return $this->watched()->create([
            'user_id' => $user->id,
            'watched_at' => Arr::get($attributes, 'watched_at', now()),
        ]);
    }

    public function watched() : MorphMany
    {
        return $this->morphMany(Watched::class, 'watchable');
    }
}