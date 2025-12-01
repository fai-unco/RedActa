<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function redactaUsers()
    {
        return $this->belongsToMany(RedactaUser::class, 'group_memberships', 'group_id', 'redacta_user_id')->as('group_membership')->withPivot(['id']);
    }

    public function documentSharedAccesses()
    {
        return $this->morphMany(DocumentSharedAccess::class, 'document_shared_accessable');
    }
}
