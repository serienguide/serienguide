<?php

namespace App\Traits\Media;

use App\Models\Ratings\Rating;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasRatings
{
    public static function bootHasRatings()
    {
        static::creating(function ($model) {
            //
        });
    }

    public function initializeHasRatings()
    {
        //
    }

    public function rateBy(User $user, array $attributes = [])
    {
        if ($attributes['rating'] == 0) {
            $this->ratingByUser($user->id)->delete();
            $this->cacheRatings();
            return null;
        }

        $rating = $this->ratingByUser($user->id);
        if (! is_null($rating)) {
            $rating->update($attributes);
            $this->cacheRatings();
            return $rating;
        }

        $attributes['user_id'] = $user->id;

        $rating = $this->ratings()->create($attributes);

        $this->cacheRatings();

        return $rating;
    }

    protected function cacheRatings() : void
    {
        $this->update([
            'vote_count' => $this->ratings()->count(),
            'vote_average' => round($this->ratings()->avg('rating'), 1),
        ]);
    }

    public function ratings() : MorphMany
    {
        return $this->morphMany(Rating::class, 'medium');
    }

    public function ratingByUser(int $user_id)
    {
        return $this->ratings()->where('user_id', $user_id)->first();
    }
}