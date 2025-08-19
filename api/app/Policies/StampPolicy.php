<?php

namespace App\Policies;

use App\Models\RedactaUser;
use App\Models\Stamp;
use Illuminate\Auth\Access\HandlesAuthorization;

class StampPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  bool $adminMode
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(RedactaUser $redactaUser, bool $adminMode)
    {
        if ($adminMode) {
            return $redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin');
        }
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Stamp  $stamp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(RedactaUser $redactaUser, Stamp $stamp)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(RedactaUser $redactaUser)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Stamp  $stamp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(RedactaUser $redactaUser, Stamp $stamp)
    {
        if ($redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin')) {
            return true;
        }
        return $redactaUser->id === $stamp->redacta_user_id;
        
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Stamp  $stamp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(RedactaUser $redactaUser, Stamp $stamp)
    {
        if ($redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin')) {
            return true;
        }
        return $redactaUser->id === $stamp->redacta_user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Stamp  $stamp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(RedactaUser $redactaUser, Stamp $stamp)
    {
        if ($redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin')) {
            return true;
        }
        return $redactaUser->id === $stamp->redacta_user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Stamp  $stamp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(RedactaUser $redactaUser, Stamp $stamp)
    {
        if ($redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin')) {
            return true;
        }
        return $redactaUser->id === $stamp->redacta_user_id;
    }
}
