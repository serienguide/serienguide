<?php

namespace App\Policies\Shows\Seasons;

use App\Models\Models\Shows\Seasons\Season;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeasonPolicy
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
     * @param  \App\Models\Models\Shows\Seasons\Season  $season
     * @return mixed
     */
    public function view(User $user, Season $season)
    {
        return ($user->id == $season->user_id);
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
     * @param  \App\Models\Models\Shows\Seasons\Season  $season
     * @return mixed
     */
    public function update(User $user, Season $season)
    {
        return ($user->id == $season->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Shows\Seasons\Season  $season
     * @return mixed
     */
    public function delete(User $user, Season $season)
    {
        return ($user->id == $season->user_id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Shows\Seasons\Season  $season
     * @return mixed
     */
    public function restore(User $user, Season $season)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Shows\Seasons\Season  $season
     * @return mixed
     */
    public function forceDelete(User $user, Season $season)
    {
        //
    }
}
