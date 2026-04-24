<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class Offer extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'amount',
        'proposal_letter',
        'status',
        'delivery_days',
    ];

    public function casts()
    {
        return [
            'amount' => 'decimal:2',
            'delivery_days' => 'integer',
        ];
    }
    
/**
 * the offer belongs to one project
 * @return BelongsTo<Project, Offer>
 */
public function project():BelongsTo
{
    return $this->belongsTo(Project::class);
}
/**

 * @return BelongsTo<User, Offer>
 */
public function user():BelongsTo
{
    return $this->belongsTo(User::class,'user_id');
}
/**

 * @return \Illuminate\Database\Eloquent\Relations\MorphMany<Attachment, Offer>
 */
public function attachments()
{
    return $this->morphMany(Attachment::class ,'attachable');

}

}
