<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;
    protected $fillable = [
        'document_id',
        'redacta_user_id',
        'stamp_id'
    ];

    public function document() {
        return $this->belongsTo(Document::class);
    }

    public function redactaUser(){
        return $this->belongsTo(RedactaUser::class);
    }

    public function stamp(){
        return $this->belongsTo(Stamp::class);
    }


}
