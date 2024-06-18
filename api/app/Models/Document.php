<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;


class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'body',
        'issue_date',
        'destinatary',
        'subject',
        'number',
        'operative_section_beginning_id',
        'true_copy_stamp_id', 
        'heading_id',
        'has_anexo_unico',
        'visibility_level_id', 
        'stamps',
        'issuer_id'
    ];
    
    public function documentCopy(){
        return $this->hasOne(DocumentCopy::class);
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

    public function operativeSectionBeginning(){
        return $this->belongsTo(OperativeSectionBeginning::class);
    }

    public function trueCopyStamp(){
        return $this->belongsTo(Stamp::class);
    }

    public function heading(){
        return $this->belongsTo(Heading::class);
    }

    public function visibilityLevel(){
        return $this->belongsTo(VisibilityLevel::class);
    }

    /*public function anexosSectionType(){
        return $this->belongsTo(AnexosSectionType::class);
    }*/

    
//hasMany
    public function documentStateHistoryItems(){
        return $this->hasMany(DocumentStateHistoryItem::class);
    }

    public function documentSignaturePresentations(){
        return $this->hasMany(DocumentSignaturePresentation::class);
    }

    public function anexos(){
        return $this->hasMany(Anexo::class);
    }

    public function signatures(){
        return $this->hasMany(Signature::class);
    }
    
    public function documentSharedAccesses(){
        return $this->hasMany(DocumentSharedAccess::class);
    }
    
    public function set($data){
        foreach ($data as $key => $value) {
            if ($key == 'body'){
                $this->setAttribute($key, json_encode($value));
            } else {
                $this->setAttribute($key, $value);
            }
        }
    }
}