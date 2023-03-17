<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSignaturePresentation extends Model
{
    use HasFactory;
    protected $fillable = [
        'creation_date' 
    ];
    //belongTo
    public function document(){
        return $this->belongsTo(Document::class);
    }

    public function redactaUser(){
        return $this->belongsTo(RedactaUser::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function signatories(){
        return $this->belongsTo(Signatory::class);
    }

    
}
