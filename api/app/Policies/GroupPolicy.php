<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\RedactaUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
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
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(RedactaUser $redactaUser, Group $group)
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
        return $redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(RedactaUser $redactaUser, Group $group)
    {
        return $redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(RedactaUser $redactaUser, Group $group)
    {
        return $redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(RedactaUser $redactaUser, Group $group)
    {
        return $redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin');

    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(RedactaUser $redactaUser, Group $group)
    {
        return $redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin');
    }
}
