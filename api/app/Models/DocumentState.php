<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document_state extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'disabled_date' 
    ];

//hasMany
    public function document_state_history_items(){
        return $this->hasMany(Document_state_history_item::class);
    }
}
