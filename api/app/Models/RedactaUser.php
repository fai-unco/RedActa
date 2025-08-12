<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;


class RedactaUser extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'last_access'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'pivot'
    ];

//hasMany

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function documentSignaturePresentations()
    {
        return $this->hasMany(DocumentSignaturePresentation::class);
    }

    public function documentStateHistoryItems()
    {
        return $this->hasMany(DocumentStateHistoryItem::class);
    }

    public function stamps()
    {
        return $this->hasMany(Stamp::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_memberships', 'redacta_user_id', 'group_id');
    }

    public function groupMemberships()
    {
        return $this->hasMany(GroupMembership::class, 'redacta_user_id');
    }

    public function documentSharedAccesses()
    {
        return $this->morphMany(DocumentSharedAccess::class, 'document_shared_accessable');
    }

}