<?php

namespace App\Policies;

use App\Models\DocumentSharedAccess;
use App\Models\RedactaUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentSharedAccessPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  int $documentId
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(RedactaUser $redactaUser, int $documentId = null)
    {
        if ($documentId) {
            $document = \App\Models\Document::find($documentId);
            if ($document && !$redactaUser->hasRole('super_admin') && !$redactaUser->hasRole('local_admin')) {
                return $document && $document->isAccessibleToRedactaUser($redactaUser, 2);
            }
        }
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\DocumentSharedAccess  $documentSharedAccess
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(RedactaUser $redactaUser, DocumentSharedAccess $documentSharedAccess)
    {
        if ($redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin')) {
            return true;
        }
        return $documentSharedAccess->document->isAccessibleToRedactaUser($redactaUser, 2);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param \App\Models\Document  $document
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(RedactaUser $redactaUser, Document $document)
    {
        if ($redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin')) {
            return true;
        }
        return $document->isAccessibleToRedactaUser($redactaUser, 1);
    }
   

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\DocumentSharedAccess  $documentSharedAccess
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(RedactaUser $redactaUser, DocumentSharedAccess $documentSharedAccess)
    {
        if ($redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin')) {
            return true;
        }
        return $documentSharedAccess->document->isAccessibleToRedactaUser($redactaUser, 1);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\DocumentSharedAccess  $documentSharedAccess
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(RedactaUser $redactaUser, DocumentSharedAccess $documentSharedAccess)
    {
        if ($redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin')) {
            return true;
        }
        return $documentSharedAccess->document->isAccessibleToRedactaUser($redactaUser, 1);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\DocumentSharedAccess  $documentSharedAccess
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(RedactaUser $redactaUser, DocumentSharedAccess $documentSharedAccess)
    {
        if ($redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin')) {
            return true;
        }
        return $documentSharedAccess->document->isAccessibleToRedactaUser($redactaUser, 1);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\DocumentSharedAccess  $documentSharedAccess
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(RedactaUser $redactaUser, DocumentSharedAccess $documentSharedAccess)
    {
        if ($redactaUser->hasRole('super_admin') || $redactaUser->hasRole('local_admin')) {
            return true;
        }
        return $documentSharedAccess->document->isAccessibleToRedactaUser($redactaUser, 1);
    }
}
