<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_resets';
    public $timestamps = false;
    protected $fillable = ['email', 'token', 'used', 'created_at'];
    protected $primaryKey = 'token';
}