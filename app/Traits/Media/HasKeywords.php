<?php

namespace App\Traits\Media;

use App\Models\Keywords\Keyword;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasKeywords
{
    public static function bootHasKeywords()
    {
        static::creating(function ($model) {
            //
        });
    }

    public function initializeHasKeywords()
    {
        //
    }

    public function keywords() : MorphToMany
    {
        return $this->morphToMany(Keyword::class, 'medium', 'keyword_medium');
    }

    protected function syncKeywordsFromTmdb(array $tmdb_keywords)
    {
        $keyword_ids = [];
        foreach ($tmdb_keywords as $key => $tmdb_keyword) {
            $keyword = Keyword::firstOrCreate([
                'id' => $tmdb_keyword['id'],
            ], [
                'name' => $tmdb_keyword['name'],
            ]);
            $keyword_ids[] = $keyword->id;
        }

        $this->keywords()->sync($keyword_ids);
    }
}