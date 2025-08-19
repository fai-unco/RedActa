<?php

namespace App\Policies;

use App\Models\Anexo;
use App\Models\RedactaUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnexoPolicy
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
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Anexo  $anexo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(RedactaUser $redactaUser, Anexo $anexo)
    {
        return $redactaUser->hasRole('super_admin') || 
            $redactaUser->hasRole('local_admin') || 
            $anexo->document->isAccessibleToRedactaUser($redactaUser, 2);
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
         return $redactaUser->hasRole('super_admin') || 
            $redactaUser->hasRole('local_admin') || 
            $anexo->document->isAccessibleToRedactaUser($redactaUser, 1);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Anexo  $anexo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(RedactaUser $redactaUser, Anexo $anexo)
    {
        return $redactaUser->hasRole('super_admin') || 
            $redactaUser->hasRole('local_admin') || 
            $anexo->document->isAccessibleToRedactaUser($redactaUser, 1);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Anexo  $anexo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(RedactaUser $redactaUser, Anexo $anexo)
    {
        return $redactaUser->hasRole('super_admin') || 
            $redactaUser->hasRole('local_admin') || 
            $anexo->document->isAccessibleToRedactaUser($redactaUser, 1);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Anexo  $anexo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(RedactaUser $redactaUser, Anexo $anexo)
    {
        return $redactaUser->hasRole('super_admin') || 
            $redactaUser->hasRole('local_admin') || 
            $anexo->document->isAccessibleToRedactaUser($redactaUser, 1);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\RedactaUser  $redactaUser
     * @param  \App\Models\Anexo  $anexo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(RedactaUser $redactaUser, Anexo $anexo)
    {
        return $redactaUser->hasRole('super_admin') || 
            $redactaUser->hasRole('local_admin') || 
            $anexo->document->isAccessibleToRedactaUser($redactaUser, 1);
    }
}
