<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document_state_history_item extends Model
{
    use HasFactory;
    

    //belongTo
    public function document(){
        return $this->belongsTo(Document::class);
    }
    public function redactaUser(){
        return $this->belongsTo(RedactaUser::class);}
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function document_state(){
        return $this->belongsTo(Document_state::class);
    }



}
