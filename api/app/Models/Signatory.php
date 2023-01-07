<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signatory extends Model
{
    use HasFactory;

//hasMany

    public function Document_signature_presentations(){
        return $this->hasMany(Document_signature_presentation::class);
    }
}
