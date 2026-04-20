<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;


use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name', 
        'last_name',
        'email',
        'password',
        'city_id',
        'role'
    ];

    protected $appends = ['full_name','avatar_url'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**

     * @return \Illuminate\Database\Eloquent\Relations\HasOne<FreelancerProfile, User>
     */
    public function profile()
    {
        return $this->hasOne(FreelancerProfile::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Project, User>
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Offer, User>
     */
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
  
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function fullName():Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->first_name} {$this->last_name}",
        );
    }

    protected function avatarUrl(): Attribute
{
    return Attribute::make(
        get: fn () => $this->avatar
            ? asset('storage/' . $this->avatar)
            : "https://ui-avatars.com/api/?name=" . urlencode($this->full_name)
    );
}

public function getAverageRatingAttribute()
{
    
    return round($this->reviews()->avg('rating') ?? 0, 1);
}




}
