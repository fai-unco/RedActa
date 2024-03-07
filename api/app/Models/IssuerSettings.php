<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssuerSettings extends Model
{
    use HasFactory;

    protected $table = 'issuer_settings';

    protected $fillable = [
        'suggested_operative_section_beginning_id',
        'suggested_true_copy_stamp_id',
        'issuer_id',
        'suggested_heading_id',
        'suggested_operative_section_last_article',
        'suggested_starting_phrase',
        'suggested_parting_phrase'
    ];

    public function issuer(){
        return $this->belongsTo(Issuer::class);
    }

    public function suggestedOperativeSectionBeginning(){
        return $this->belongsTo(OperativeSectionBeginning::class);
    }

    public function suggestedTrueCopyStamp(){
        return $this->belongsTo(Stamp::class);
    }
    
    public function suggestedHeading(){
        return $this->belongsTo(Heading::class);
    }

    public function set($data){
        foreach ($data as $key => $value) {
            $this->setAttribute($key, $value);
        }
        $this->save();
    }
}
