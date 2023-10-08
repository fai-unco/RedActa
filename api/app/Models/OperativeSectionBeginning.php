<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperativeSectionBeginning extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'content',
        'issuer_id'
    ];

}
