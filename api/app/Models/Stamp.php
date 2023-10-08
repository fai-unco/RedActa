<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stamp extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'full_name',
        'position',
        'issuer_id'
    ];
}
