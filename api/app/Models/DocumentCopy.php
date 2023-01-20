<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentCopy extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_document_copy';
    const CREATED_AT = 'creation_date';

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
