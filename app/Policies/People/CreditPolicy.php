<?php

namespace App\Policies\People;

use App\Models\Models\People\Credit;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CreditPolicy
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
     * @param  \App\Models\Models\People\Credit  $credit
     * @return mixed
     */
    public function view(User $user, Credit $credit)
    {
        return ($user->id == $credit->user_id);
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
     * @param  \App\Models\Models\People\Credit  $credit
     * @return mixed
     */
    public function update(User $user, Credit $credit)
    {
        return ($user->id == $credit->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\People\Credit  $credit
     * @return mixed
     */
    public function delete(User $user, Credit $credit)
    {
        return ($user->id == $credit->user_id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\People\Credit  $credit
     * @return mixed
     */
    public function restore(User $user, Credit $credit)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Models\People\Credit  $credit
     * @return mixed
     */
    public function forceDelete(User $user, Credit $credit)
    {
        //
    }
}
