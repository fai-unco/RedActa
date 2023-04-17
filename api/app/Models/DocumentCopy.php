<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentCopy extends Model
{
    use HasFactory;

    public function document()
    {
        return $this->hasOne(Document::class);
    }
}
