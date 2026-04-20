<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Skill extends Model
{
    protected $fillable = [
        'name'
    ];
    /**
     * @return BelongsToMany<FreelancerProfile, Skill, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function freelancers():BelongsToMany
    {
        return $this->belongsToMany(FreelancerProfile::class, 'freelancer_skill')
                    ->withPivot('years_of_experience')
                    ->withTimestamps();
    }
}
