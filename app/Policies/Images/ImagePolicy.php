<?php

namespace App\Policies\Images;

use App\Models\Models\Images\Image;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImagePolicy
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
     * @param  \App\Models\Models\Images\Image  $image
     * @return mixed
     */
    public function view(User $user, Image $image)
    {
        return ($user->id == $image->user_id);
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
     * @param  \App\Models\Models\Images\Image  $image
     * @return mixed
     */
    public function update(User $user, Image $image)
    {
        return ($user->id == $image->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Images\Image  $image
     * @return mixed
     */
    public function delete(User $user, Image $image)
    {
        return ($user->id == $image->user_id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Images\Image  $image
     * @return mixed
     */
    public function restore(User $user, Image $image)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\Images\Image  $image
     * @return mixed
     */
    public function forceDelete(User $user, Image $image)
    {
        //
    }
}
