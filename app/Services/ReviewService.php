<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Review;
use App\Models\FreelancerProfile;

class ReviewService
{
    public function createReview(Project $project, array $data): Review
    {
        if ($project->status !== 'closed') {
            abort(400, 'You can only review a project after it is closed.');
        }

        if (auth('sanctum')->id() !== $project->client_id) {
            abort(403, 'You are not allowed to review this project.');
        }

        $freelancerProfile = FreelancerProfile::where('user_id', $project->freelancer_id)->firstOrFail();

      
        return Review::create([
            'reviewer_id'     => auth('sanctum')->id(),
            'project_id'      => $project->id,
            'rating'          => $data['rating'],
            'comment'         => $data['comment'] ?? null,
            'reviewable_type' => FreelancerProfile::class,
            'reviewable_id'   => $freelancerProfile->id,
        ]);
    }
}
