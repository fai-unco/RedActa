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

    public function Documents()
    {
        return $this->hasMany(Document::class);
    }
    public function roles(){
        return $this->hasMany(Role::class);
    }
    public function document_signature_presentations()
    {
        return $this->hasMany(Document_signature_presentation::class);
    }
    public function document_state_history_items()
    {
        return $this->hasMany(Document_state_history_item::class);
    }

}