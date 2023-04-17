<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentState extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
    ];

//hasMany
    public function documentStateHistoryItems(){
        return $this->hasMany(DocumentStateHistoryItem::class);
    }
}
