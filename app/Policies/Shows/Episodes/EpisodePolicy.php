<?php

namespace App\Policies\Shows\Episodes;

use App\Models\Models\Shows\Episodes\Episode;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EpisodePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Shows\Episodes\Episode  $episode
     * @return mixed
     */
    public function view(User $user, Episode $episode)
    {
        return ($user->id == $episode->user_id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Shows\Episodes\Episode  $episode
     * @return mixed
     */
    public function update(User $user, Episode $episode)
    {
        return ($user->id == $episode->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Shows\Episodes\Episode  $episode
     * @return mixed
     */
    public function delete(User $user, Episode $episode)
    {
        return ($user->id == $episode->user_id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Shows\Episodes\Episode  $episode
     * @return mixed
     */
    public function restore(User $user, Episode $episode)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Shows\Episodes\Episode  $episode
     * @return mixed
     */
    public function forceDelete(User $user, Episode $episode)
    {
        //
    }
}
