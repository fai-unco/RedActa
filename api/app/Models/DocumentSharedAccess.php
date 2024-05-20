<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSharedAccess extends Model
{
    use HasFactory;
    protected $table = 'documents_shared_accesses';
    protected $with = ['redactaUser'];

    protected $fillable = [
        'document_id',
        'redacta_user_id', 
        'access_mode_id'
    ];

    public function document() {
        return $this->belongsTo(Document::class);
    }

    public function redactaUser() {
        return $this->belongsTo(RedactaUser::class);
    }

    public function accessMode() {
        return $this->belongsTo(AccessMode::class);
    }
}
