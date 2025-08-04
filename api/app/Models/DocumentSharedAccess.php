<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSharedAccess extends Model
{
    use HasFactory;
    protected $table = 'documents_shared_accesses';

    protected $fillable = [
        'document_id',
        'access_mode_id',
        'document_shared_accessable_type',
        'document_shared_accessable_id',
        'redacta_user_id',
    ];

    protected $hidden = ['document_shared_accessable_type', 'document_shared_accessable_id'];

    protected $maps = [
        'document_shared_accessable_type' => 'resource_type',
        'document_shared_accessable_id' => 'resource_id',
    ];

    protected $appends = ['resource_type', 'resource_id'];

    public function document() {
        return $this->belongsTo(Document::class);
    }

    public function accessMode() {
        return $this->belongsTo(AccessMode::class);
    }

    public function documentSharedAccessable() {
        return $this->morphTo();
    }

    public function getResourceTypeAttribute() {
            return $this->attributes['document_shared_accessable_type'] == 'App\Models\Group' ? 'group' : 'redactaUser';
    }

    public function getResourceIdAttribute() {
        return $this->attributes['document_shared_accessable_id'];
    }

    public function redactaUser() {
        return $this->belongsTo(RedactaUser::class);
    }
}
