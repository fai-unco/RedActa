<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\RedactaUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
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
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(RedactaUser $redactaUser, Document $document)
    {
        if ($redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin')) {
            return true;
        }
        return $document->isAccessibleToRedactaUser($redactaUser, 2);
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
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(RedactaUser $redactaUser, Document $document)
    {
        if ($redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin')) {
            return true;
        }
        return $document->isAccessibleToRedactaUser($redactaUser, 1);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(RedactaUser $redactaUser, Document $document)
    {
        if ($redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin')) {
            return true;
        }
        return $document->isAccessibleToRedactaUser($redactaUser, 1);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(RedactaUser $redactaUser, Document $document)
    {
        if ($redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin')) {
            return true;
        }
        return $document->isAccessibleToRedactaUser($redactaUser, 1);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(RedactaUser $redactaUser, Document $document)
    {
        if ($redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin')) {
            return true;
        }
        return $document->isAccessibleToRedactaUser($redactaUser, 1);
    }
}
