<?php

namespace App\Models\People;

use App\Traits\HasSlug;
use App\Traits\Media\HasImages;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Person extends Model
{
    use HasFactory,
        HasImages,
        HasModelPath,
        HasSlug;

    const ROUTE_NAME = 'people';
    const VIEW_PATH = 'people';

    protected $appends = [
        //
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'birthday_at',
        'deathday_at',
    ];

    protected $fillable = [
        'id',
        'name',
        'birthday_at',
        'deathday_at',
        'known_for_department',
        'gender',
        'biography',
        'place_of_birth',
        'poster_path',
        'backdrop_path',
        'homepage',
        'vote_count',
        'vote_average',
        'tmdb_vote_count',
        'tmdb_vote_average',
        'tmdb_trending',
        'tmdb_popularity',
    ];

    public $incrementing = false;

    public function isDeletable() : bool
    {
        return true;
    }

    public function updateFromTmdb($attributes = null)
    {
        if (is_null($attributes)) {
            $attributes = \App\Apis\Tmdb\Person::find($this->id);
        }
        if (empty($attributes)) {
            return;
        }
        $this->update($attributes);
        $this->syncFromTmdb($attributes);
    }

    protected function syncFromTmdb($attributes)
    {
        // $this->createImageFromTmdb('still', $attributes['profile_path']);
    }

    public function setSlug(bool $force = false) : void
    {
        if (! $this->isDirty('name') && $force === false) {
            return;
        }

        if (empty($this->name)) {
            $this->attributes['slug'] = Str::uuid();
            return;
        }

        $slug = Str::slug($this->name, '-', 'de');
        if (self::where('id', '!=', $this->id)->slug($slug)->exists()) {
            $slug .= '-' . ((self::where('name', $this->name)->count()) + 1);
        }

        $this->attributes['slug'] = $slug;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            Str::singular($this->base_route) => $this->slug
        ];
    }

    public function getTmdbPathAttribute()
    {
        if (empty($this->id)) {
            return null;
        }

        return 'https://www.themoviedb.org/person/' . $this->id;
    }

    public function getPosterPathAttribute() : string
    {
        return $this->profile_path;
    }
}