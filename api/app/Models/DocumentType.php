<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class DocumentType extends Model
{
    use HasFactory,SoftDeletes;

    //protected $primaryKey = 'document_type_id';

    protected $fillable = [
        'description',
        'view',
        'actionInOperativeSection'
    ];

    protected $hidden = ['view', 'actionInOperativeSection'];


    //hasMany
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
