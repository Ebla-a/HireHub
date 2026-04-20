<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class FreelancerProfile extends Model
{
    protected $fillable = 
    [
        'user_id',
        'bio',
        'hourly_rate',
        'availability',
        'phone_number',
        'portfolio_links',

    ];
    protected $appends = ['rating_stars', 'joined_since'];
    protected function casts():array
    {
        return [
        'hourly_rate' => 'decimal:2', 
        'is_verified' => 'boolean',
        'portfolio_links' => 'array',
        ];
        
    }
    
public function user():BelongsTo
{
    return $this->belongsTo(User::class ,'user_id');
}

public function offers():HasManyThrough{
    return $this->hasManyThrough(
        Offer::class ,
        User::class,
        'id', // user.id
        'user_id',//offers.user.id
        'user_id' , // freelancer_profiles.user_id
        'id'
    );
}
/**
 * @return BelongsToMany<Skill, FreelancerProfile, \Illuminate\Database\Eloquent\Relations\Pivot>
 */
public function skills():BelongsToMany 
{
    return $this->belongsToMany(Skill::class ,'freelancer_skill','freelancer_profile_id', 'skill_id')
    ->withPivot('years_of_experience')
    ->withTimestamps();
}
/**
 * @return MorphMany<Review, FreelancerProfile>
 */
public function reviews():MorphMany
{
    return $this->morphMany(Review::class ,'reviewable');

}
protected function ratingStars():Attribute
{
 return Attribute::make(
    get : function()
    {
        $average = $this->reviews()->avg('rating') ?? 0;
         return number_format($average, 1) . ' ⭐';
    }
 );

}

protected function joinedSince(): Attribute
{
    return Attribute::make(
        get: fn () => "Member since " . $this->created_at->format('F Y'),
    );
}

public function scopeActive($query)
{
    return $query->where('is_verified', true);
}

public function scopeAvailable($query)
{
    return $query->where('availability', true);
}

}
