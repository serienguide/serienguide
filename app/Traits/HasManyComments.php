<?php

namespace App\Traits;

use App\Models\Commments\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasManyComments
{
    public function comments() : MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function commentByUser(User $user, array $attributes = []) {

        $attributes['user_id'] = $user->id;
        $comment = $this->comments()->create($attributes);

        return $comment;
    }
}