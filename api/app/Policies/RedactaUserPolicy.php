<?php

namespace App\Policies;

use App\Models\RedactaUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class RedactaUserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(RedactaUser $redactaUser)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\RedactaUser  $currentUser
     * @param  \App\Models\RedactaUser  $redactaUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(RedactaUser $currentUser, RedactaUser $redactaUser)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(RedactaUser $redactaUser)
    {
        if ($redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\RedactaUser  $currentUser
     * @param  \App\Models\RedactaUser  $redactaUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(RedactaUser $currentUser, RedactaUser $redactaUser)
    {
        if ($currentUser->hasRole('super_admin') || $current->hasRole('local_admin')) {
            return true;
        }
        return $currentUser->id === $redactaUser->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\RedactaUser  $currentUser
     * @param  \App\Models\RedactaUser  $redactaUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(RedactaUser $currentUser, RedactaUser $redactaUser)
    {
        if ($currentUser->hasRole('super_admin') || $current->hasRole('local_admin')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\RedactaUser  $currentUser
     * @param  \App\Models\RedactaUser  $redactaUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(RedactaUser $currentUser, RedactaUser $redactaUser)
    {
        if ($currentUser->hasRole('super_admin') || $current->hasRole('local_admin')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\RedactaUser  $currentUser
     * @param  \App\Models\RedactaUser  $redactaUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(RedactaUser $currentUser, RedactaUser $redactaUser)
    {
        if ($currentUser->hasRole('super_admin') || $current->hasRole('local_admin')) {
            return true;
        }
        return false;
    }
}
