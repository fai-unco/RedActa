<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;


class Document extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'body',
        'issue_date',
        'issue_place',
        'destinatary',
        'subject',
        'ad_referendum',
        'number'
        
    ];

    public function document_copy()
    {
        return $this->hasOne(Document_copy::class);
    }

    
    
//belonTo
    public function documentType(){
        return $this->belongsTo(DocumentType::class);
    }

    public function redactaUser(){
        return $this->belongsTo(RedactaUser::class);
    }

    public function issuer(){
        return $this->belongsTo(Issuer::class);
    }

    
//hasMany
    public function document_state_history_items(){
        return $this->hasMany(Document_state_history_item::class);
    }
    public function document_signature_presentations(){
        return $this->hasMany(Document_signature_presentation::class);
    }

    public function set($data){
        $issuer = Issuer::find($data['issuer_id']);
        $user = RedactaUser::find(Auth::id());
        $documentType = DocumentType::find($data['document_type_id']);

        $this->redactaUser()->associate($user);
        foreach ($data as $key => $value) {
            if ($key == 'document_type_id'){
                $this->documentType()->associate($documentType);
            } else if  ($key == 'issuer_id'){
                $this->issuer()->associate($issuer);
            } else {
                $this->setAttribute($key, $value);
            }
        }
    }
}
