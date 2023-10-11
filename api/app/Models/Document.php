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
        'issue_place',
        'destinatary',
        'subject',
        'ad_referendum',
        'number',
        'operative_section_beginning_id',
        'true_copy_stamp_id'
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

    public function set($data){
        $issuer = Issuer::find($data['issuer_id']);
        $user = RedactaUser::find(Auth::id());
        $documentType = DocumentType::find($data['document_type_id']);
        //$anexosSectionType = AnexosSectionType::find($data['anexos_section_type_id']);        
        $this->redactaUser()->associate($user);
        
        foreach ($data as $key => $value) {
            if ($key == 'document_type_id'){
                $this->documentType()->associate($documentType);
            } else if  ($key == 'issuer_id'){
                $this->issuer()->associate($issuer);
            } else if ($key == 'body'){
                $this->setAttribute($key, json_encode($value));
            } 
            
            /*else if ($key == 'anexos_section_type_id'){
                $this->anexosSectionType()->associate($anexosSectionType);
            } */
            /*else if ($key == 'anexos'){
                foreach ($data->anexos as $anexoData) {
                    //Creates a new anexo
                    $newAnexo = new Anexo();
                    $newAnexo->title = $anexoData->title;
                    $newAnexo->subtitle = $anexoData->subtitle;
                    $newAnexo->content = $anexoData->content;
                    $newAnexo->save();
                    
                    
                    //Associates the anexo with the previously uploaded file
                    $file = File::find($anexoData->fileId);
                    // Conviene crear un método en File que reciba fileId para asociarlo con newAnexo 
                    $file->anexo()->associate($newAnexo);
                }        
            } */
            else {
                $this->setAttribute($key, $value);
            }
        }
    }
}
