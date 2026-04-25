<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;


class Project extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'budget_type',
        'budget',
        'deadline',
        'status',
        'user_id'

    ];

    protected $appends = ['formatted_budget', 'time_left'];

    public function casts():array
    {
        return [
            'budget' =>'decimal:2',
            'deadline' => 'datetime',
        ];

    }

    /**
     * the project belongs to one user
     * @return BelongsTo<User, Project>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * the project  may has many offers 
     * @return HasMany<Project, Project>
     */
    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }
    /**
     * m2m with tags
     * @return BelongsToMany<Project, Project, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'project_tags');
    }
    /**
     * @return MorphMany<Attachment, Project>
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
    /**
     * @return MorphMany<Review, Project>
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    protected function formattedBudget(): Attribute
{
    return Attribute::make(
        get: fn () => $this->budget_type === 'hourly' 
            ? "$ {$this->budget}/hr" 
            : "$ {$this->budget} (Fixed Price)"
    );
}
protected function timeLeft(): Attribute
{
    return Attribute::make(
        get: function () {
            $days = now()->diffInDays($this->deadline, false);
            return $days <= 0 ? 'Expired' : "$days days left";
        }
    );
}


public function scopeOpen($query)
{
    return $query->where('status', 'open');
}

public function scopeHighBudget($query, $amount = 1000)
{
    return $query->where('budget', '>=', $amount);
}


protected static function booted()
{
    static::created(function ($project) {
        Cache::forget('projects_list');
    });

    static::updated(function ($project) {
        Cache::forget("project_{$project->id}");
        Cache::forget("projects_list");
    });

    static::deleted(function ($project) {
        Cache::forget("project_{$project->id}");
        Cache::forget("projects_list");
    });

}

}
