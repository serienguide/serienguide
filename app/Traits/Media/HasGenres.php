<?php

namespace App\Traits\Media;

use App\Models\Genres\Genre;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasGenres
{
    public static function bootHasGenres()
    {
        static::creating(function ($model) {
            //
        });
    }

    public function initializeHasGenres()
    {
        //
    }

    public function genres() : MorphToMany
    {
        return $this->morphToMany(Genre::class, 'medium', 'genre_medium');
    }

    protected function syncGenresFromTmdb(array $tmdb_genres)
    {
        $genre_ids = [];
        foreach ($tmdb_genres as $key => $tmdb_genre) {
            $genre = Genre::firstOrCreate([
                'id' => $tmdb_genre['id'],
            ], [
                'name' => $tmdb_genre['name'],
            ]);
            $genre_ids[] = $genre->id;
        }

        $this->genres()->sync($genre_ids);
    }

    // scopeHasGenres
    // scopeHasNotGenres
}