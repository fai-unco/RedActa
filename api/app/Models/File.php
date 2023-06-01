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

    public function anexo(){
        return $this->belongsTo(Anexo::class);
    }

    public function redactaUser(){
        return $this->belongsTo(RedactaUser::class);
    }

}
