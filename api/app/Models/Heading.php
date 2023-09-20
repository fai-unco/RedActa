<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\File;


class Heading extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'content',
        'index'        
    ];

    public function issuer(){
        return $this->belongsTo(Issuer::class);
    }

    public function file(){
        return $this->morphOne(File::class, 'fileable');
    }

    public function set($data){
        foreach ($data as $key => $value) {
            if($key != 'file_id'){
                $this->setAttribute($key, $value);
            }
        }
        $this->save();
        $file = File::find($data['file_id']);
        if($file && $this->file != $file){
            if($this->file){
                $this->file()->update(['fileable_id' => $this->id]);
            } else {
                $this->file()->save($file);
            }
        }
    
    }

    
}
