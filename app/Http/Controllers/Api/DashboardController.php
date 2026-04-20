<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(): JsonResponse
    {
        $stats = $this->dashboardService->getStats(auth('sanctum')->user());

        return response()->json([
            'status' => 'success',
            'role'   => auth('sanctum')->user()->role,
            'data'   => $stats
        ]);
    }
}