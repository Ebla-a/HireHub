<?php



namespace App\Services;

use App\Jobs\RecalculateFreelancerRating;
use App\Models\Project;
use App\Models\Review;
use App\Models\FreelancerProfile;
use App\Notifications\ReviewReceivedNotification;

class ReviewService
{
    /**
     * 
     * @param Project $project
     * @param array $data
     * @return Review
     */
    public function createReview(Project $project, array $data): Review
    {    
        if ($project->status !== 'closed') {
            abort(400, 'You can only review a project after it is closed.');
        }

       
        if (auth('sanctum')->id() !== $project->client_id) {
            abort(403, 'You are not allowed to review this project.');
        }

     // prevent dublicate
        $existing = Review::where('project_id', $project->id)
            ->where('reviewer_id', auth('sanctum')->id())
            ->first();

        if ($existing) {
            abort(400, 'You have already reviewed this project.');
        }

        // get freelanc profile
        $freelancerProfile = FreelancerProfile::where('user_id', $project->freelancer_id)
            ->firstOrFail();

        // create review
        $review = Review::create([
            'reviewer_id'     => auth('sanctum')->id(),
            'project_id'      => $project->id,
            'rating'          => $data['rating'],
            'comment'         => $data['comment'] ?? null,
            'reviewable_type' => FreelancerProfile::class,
            'reviewable_id'   => $freelancerProfile->id,
        ]);

        // send notifcation
        $freelancerProfile->user->notify(new ReviewReceivedNotification($review));

        // calculate the avg of rating
        dispatch(new RecalculateFreelancerRating($freelancerProfile->id));

        return $review;
    }
}
