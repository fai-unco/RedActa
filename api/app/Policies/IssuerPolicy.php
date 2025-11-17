<?php

namespace App\Policies;

use App\Models\Issuer;
use App\Models\RedactaUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class IssuerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param boolean $adminMode
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
     * @param  \App\Models\Issuer  $issuer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(RedactaUser $redactaUser, Issuer $issuer)
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
        return $redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Issuer  $issuer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(RedactaUser $redactaUser, Issuer $issuer)
    {
        return $redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Issuer  $issuer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(RedactaUser $redactaUser, Issuer $issuer)
    {
        return $redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Issuer  $issuer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(RedactaUser $redactaUser, Issuer $issuer)
    {
        return $redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Issuer  $issuer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(RedactaUser $redactaUser, Issuer $issuer)
    {
        return $redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin');
    }
}
