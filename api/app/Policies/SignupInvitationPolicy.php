<?php

namespace App\Policies;

use App\Models\RedactaUser;
use App\Models\SignupInvitation;
use Illuminate\Auth\Access\HandlesAuthorization;

class SignupInvitationPolicy
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
        return $redactaUser->hasAnyRole(['super_admin', 'local_admin']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\SignupInvitation  $signupInvitation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(RedactaUser $redactaUser, SignupInvitation $signupInvitation)
    {
        return $redactaUser->hasAnyRole(['super_admin', 'local_admin']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(RedactaUser $redactaUser)
    {
        return $redactaUser->hasAnyRole(['super_admin', 'local_admin']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\SignupInvitation  $signupInvitation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(RedactaUser $redactaUser, SignupInvitation $signupInvitation)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\SignupInvitation  $signupInvitation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(RedactaUser $redactaUser, SignupInvitation $signupInvitation)
    {
        return $redactaUser->hasAnyRole(['super_admin', 'local_admin']);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\SignupInvitation  $signupInvitation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(RedactaUser $redactaUser, SignupInvitation $signupInvitation)
    {
        return $redactaUser->hasAnyRole(['super_admin', 'local_admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\SignupInvitation  $signupInvitation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(RedactaUser $redactaUser, SignupInvitation $signupInvitation)
    {
        return $redactaUser->hasAnyRole(['super_admin', 'local_admin']);
    }
}
