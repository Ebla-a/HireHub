<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Models\Project;
use App\Services\ReviewService;

class ReviewController extends Controller
{
    protected ReviewService $service;

    public function __construct(ReviewService $service)
    {
        $this->service = $service;
    }

    public function store(ReviewRequest $request, Project $project)
    {
        $review = $this->service->createReview($project, $request->validated());

        return response()->json([
            'message' => 'Review submitted successfully.',
            'data'    => $review
        ], 201);
    }
}
