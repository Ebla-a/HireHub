<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attachment extends Model
{
    protected $fillable = ['file_name', 'file_path', 'file_type', 'collection_name'];

    public function attachable():MorphTo
    {
        return $this->morphTo();
    }
}
