<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignupInvitation extends Model
{
    use HasFactory;
    protected $fillable = [
        'redacta_user_id',
        'token', 
        'email',  
    ];

    public function redactaUser()
    {
        return $this->belongsTo(RedactaUser::class, 'redacta_user_id');
    }

    public function markAsUsed()
    {
        $this->used_at = now();
        $this->save();
    }

    public function isValid() {
        if ($this->used_at !== null || $this->created_at->diffInHours(now()) > 24) {
            return false;
        }
        return true;
    }
}
