<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Cache;

class Review extends Model
{
    protected $fillable = [
        'reviewer_id',
        'rating',
        'comment',
        'project_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Review>
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Project, Review>
     */
    public function project()

    {
        return $this->belongsTo(Project::class);
    }


    /**
     * @return MorphTo<Model, Review>
     */
    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }


    protected static function booted()
{
    static::created(function ($review) {
     
        Cache::forget("freelancer_profile_{$review->reviewable_id}");
        Cache::forget("project_{$review->project_id}");
        Cache::forget("freelancers_list");
    });
}

}
