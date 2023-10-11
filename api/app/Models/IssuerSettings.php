<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssuerSettings extends Model
{
    use HasFactory;

    protected $table = 'issuer_settings';

    protected $fillable = [
        'operative_section_beginning_id',
        'ad_referendum_operative_section_beginning_id',
        'true_copy_stamp_id',
        'issuer_id'
    ];

    public function issuer(){
        return $this->belongsTo(Issuer::class);
    }

    public function set($data){
        foreach ($data as $key => $value) {
            $this->setAttribute($key, $value);
        }
        $this->save();
    }
}
