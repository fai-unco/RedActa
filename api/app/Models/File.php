<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
    ];

    public function fileable(){
        return $this->morphTo();
    }

    public function redactaUser(){
        return $this->belongsTo(RedactaUser::class);
    }

}
