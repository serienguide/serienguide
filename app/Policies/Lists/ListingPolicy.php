<?php

namespace App\Policies\Lists;

use App\Models\Models\Lists\Listing;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ListingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(?User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Lists\Listing $list
     * @return mixed
     */
    public function view(?User $user, Listing $list)
    {
        return true;
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
     * @param  \App\Models\Models\Lists\Listing $list
     * @return mixed
     */
    public function update(User $user, Listing $list)
    {
        return ($user->id == $list->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Lists\Listing $list
     * @return mixed
     */
    public function delete(User $user, Listing $list)
    {
        return ($user->id == $list->user_id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Lists\Listing $list
     * @return mixed
     */
    public function restore(User $user, Listing $list)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Lists\Listing $list
     * @return mixed
     */
    public function forceDelete(User $user, Listing $list)
    {
        //
    }
}
