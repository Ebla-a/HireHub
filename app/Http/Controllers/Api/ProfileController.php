<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\ProfileUpdateRequest ;

use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }
    public function index()
{
   
   $freelancers =  $this->profileService->getAllFreelancers();                           

    return response()->json([
        'status' => 'success',
        'data'   => $freelancers
    ]);
}
    public function show(Request $request)
{
    $userWithProfile = $this->profileService->getProfile($request->user());

    return response()->json([
        'status' => 'success',
        'data'   => $userWithProfile
    ]);
}


    public function update(ProfileUpdateRequest $request)
    {

        $user = $this->profileService->updateProfile(
            Auth::user(), 
            $request->validated() 
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);
    }


}