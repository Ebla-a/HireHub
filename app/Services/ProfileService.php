<?php

namespace App\Services;

use App\Models\FreelancerProfile;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProfileService
{
/**
 * get profile with caching
 * @param User $user
 * @return User
 */
public function getProfile(User $user)
{
    $cacheKey = "profile_{$user->id}";
    return Cache::remember($cacheKey ,600 , function() use ($user)
    {
          return $user->load([
        'profile.skills', 
        'profile.reviews'
    ]);
    });
}

/**
 * get all freelancers (cached)
 * @return \Illuminate\Pagination\LengthAwarePaginator
 */
public function getAllFreelancers()
{
   return Cache::remember('freelancers_list', 300, function () {
            return FreelancerProfile::active()
                ->available()
                ->with(['user', 'skills'])
                ->withAvg('reviews', 'rating')
                ->orderByDesc('reviews_avg_rating')
                ->paginate(15);
        });
}

    /**
     *  update profile + clear cache
     * @param User $user
     * @param array $data
     */
public function updateProfile(User $user, array $data)
{
    return DB::transaction(function () use ($user, $data) {

            $profile = $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                $data
            );

            if (isset($data['skills'])) {
                $profile->skills()->sync($data['skills']);
            }

            return $user->load(['profile.skills']);
        });
   
            

}

}

    

    