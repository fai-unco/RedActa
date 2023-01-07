<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

//belongTo
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function redacta_user()
    {
        return $this->belongsTo(RedactaUser::class);
    }
}
