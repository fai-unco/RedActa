<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Issuer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description',
        'phone',
        'email',
        'city',
        'province',
        'website_url'
    ];

    public function documents(){
        return $this->hasMany(Document::class);
    }

    public function set($data){
        foreach ($data as $key => $value) {
            $this->setAttribute($key, $value);
        }
        $this->save();
    }
}
