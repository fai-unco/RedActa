<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMembership extends Model
{
    use HasFactory;
    protected $fillable = [
        'redacta_user_id',
        'group_id',
    ];

    public function redactaUser()
    {
        return $this->belongsTo(RedactaUser::class, 'redacta_user_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
