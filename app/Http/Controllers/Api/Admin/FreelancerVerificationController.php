<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\FreelancerProfile;


class FreelancerVerificationController extends Controller
{
  public function verify(FreelancerProfile $profile)
{

    if (empty($profile->bio) || empty($profile->hourly_rate)) {
        return response()->json([
            'message' => 'Cannot verify. Profile information (bio, rate) is incomplete.'
        ], 422);
    }

    $profile->update([
        'is_verified' => true,
        // 'verified_at' => now(), 
    ]);

    return response()->json([
        'status' => 'success',
        'message' => "Freelancer {$profile->user->name} is now verified."
    ]);
}
}