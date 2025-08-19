<?php

namespace App\Policies;

use App\Models\GroupMembership;
use App\Models\RedactaUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupMembershipPolicy
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
     * @param  \App\Models\GroupMembership  $groupMembership
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(RedactaUser $redactaUser, GroupMembership $groupMembership)
    {
        if ($redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin')) {
            return true;
        }
        return $groupMembership->group->redactaUsers->contains($redactaUser);
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
     * @param  \App\Models\GroupMembership  $groupMembership
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(RedactaUser $redactaUser, GroupMembership $groupMembership)
    {
        return $redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\GroupMembership  $groupMembership
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(RedactaUser $redactaUser, GroupMembership $groupMembership)
    {
        return $redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\GroupMembership  $groupMembership
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(RedactaUser $redactaUser, GroupMembership $groupMembership)
    {
        return $redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\GroupMembership  $groupMembership
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(RedactaUser $redactaUser, GroupMembership $groupMembership)
    {
        return $redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin');
    }
}
