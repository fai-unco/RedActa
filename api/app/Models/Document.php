<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


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
        'ad_referendum'
        
    ];

    public function document_copy()
    {
        return $this->hasOne(Document_copy::class);
    }

    
    
//belonTo
    public function document_type(){
        return $this->belongsTo(Document_type::class);
    }

    public function redactaUser(){
        return $this->belongsTo(RedactaUser::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    
//hasMany
    public function document_state_history_items(){
        return $this->hasMany(Document_state_history_item::class);
    }
    public function document_signature_presentations(){
        return $this->hasMany(Document_signature_presentation::class);
    }
}
