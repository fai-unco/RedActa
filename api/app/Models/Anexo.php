<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anexo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'content',
        'index'        
    ];

    public function file(){
        return $this->morphOne('App\Models\File', 'fileable');    
    }

    public function document(){
        return $this->belongsTo(Document::class);
    }

    public function set($index, $title, $subtitle, $content, $document, $file){
        $this->index = $index;
        $this->content = $content;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->document()->associate($document);
        $this->save();
        if($file && $this->file != $file){
            if($this->file){
                $this->file()->update(['fileable_id' => $this->id]);
            } else {
                $this->file()->save($file);
            }
        }
    }



}
