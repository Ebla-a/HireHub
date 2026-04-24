<?php

namespace App\Services;

use App\Models\FreelancerProfile;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class ProfileService
{
/**
 * Summary of getProfile
 * @param User $user
 * @return User
 */
public function getProfile(User $user)
{
    return $user->load([
        'profile.skills', 
        'profile.reviews'
    ]);


}


public function getAllFreelancers()
{
    return FreelancerProfile::active()
        ->available()
        ->with(['user', 'skills'])
        ->withAvg('reviews', 'rating')
        ->orderByDesc('reviews_avg_rating')
        ->paginate(15);
}

    /**
     * updata the data and skills in one operation (Transaction)
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

    

    