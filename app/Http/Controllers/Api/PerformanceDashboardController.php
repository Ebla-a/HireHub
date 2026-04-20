<?php

namespace App\Http\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApiLog;
use Illuminate\Support\Facades\DB;

class PerformanceDashboardController extends Controller
{
 
    public function getStats()
    {
        return response()->json([
        'summary' => [
            'total_requests' => ApiLog::count(),
            'avg_duration'   => round(ApiLog::avg('duration'), 4),
        ],
        'slowest_endpoints' => ApiLog::slowest(5)->get(), 
        'heavy_queries'     => ApiLog::heavyQueries(5)->get(),
    ]);
    }
}

