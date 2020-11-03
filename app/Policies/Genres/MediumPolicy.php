<?php

namespace App\Policies\Genres;

use App\Models\Models\Genres\Medium;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediumPolicy
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
     * @param  \App\Models\Models\Genres\Medium  $medium
     * @return mixed
     */
    public function view(User $user, Medium $medium)
    {
        return ($user->id == $medium->user_id);
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
     * @param  \App\Models\Models\Genres\Medium  $medium
     * @return mixed
     */
    public function update(User $user, Medium $medium)
    {
        return ($user->id == $medium->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Genres\Medium  $medium
     * @return mixed
     */
    public function delete(User $user, Medium $medium)
    {
        return ($user->id == $medium->user_id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Genres\Medium  $medium
     * @return mixed
     */
    public function restore(User $user, Medium $medium)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Genres\Medium  $medium
     * @return mixed
     */
    public function forceDelete(User $user, Medium $medium)
    {
        //
    }
}
