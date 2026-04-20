<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class City extends Model
{
    protected $fillable = ['name' ,'country_id'];
    /**
     * @return BelongsTo<Country, City>
     */
    public function country():BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
    public function attachments()
    {
        return $this->morphMany(Attachment::class ,'attachable');
    }
  
}
