<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        if ($this->used_at !== null || (Carbon::parse($this->created_at))->diffInHours(now()) > 24) {
            return false;
        }
        return true;
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d-m-Y H:i:s', strtotime($value));
    }
}
