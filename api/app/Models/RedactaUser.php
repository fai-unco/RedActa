<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class RedactaUser extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes;

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
    ];

//hasMany

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function roles(){
        return $this->hasMany(Role::class);
    }

    public function documentSignaturePresentations()
    {
        return $this->hasMany(DocumentSignaturePresentation::class);
    }

    public function documentStateHistoryItems()
    {
        return $this->hasMany(DocumentStateHistoryItem::class);
    }

}