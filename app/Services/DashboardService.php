<?php

namespace App\Services;

use App\Models\User;
use App\Models\Project;
use App\Models\Offer;

class DashboardService
{
    public function getStats(User $user): array
    {
        return $user->role === 'client' 
            ? $this->getClientStats($user) 
            : $this->getFreelancerStats($user);
    }

    private function getClientStats(User $user): array
    {
        return [
            'total_projects'    => $user->projects()->count(),
            'active_projects'   => $user->projects()->where('status', 'in_progress')->count(),
            'completed_projects'=> $user->projects()->where('status', 'completed')->count(),
            'total_spent'       => $user->projects()
                                    ->join('offers', 'projects.id', '=', 'offers.project_id')
                                    ->where('offers.status', 'accepted')
                                    ->sum('offers.amount'),
            'pending_offers'    => Offer::whereIn('project_id', $user->projects()->pluck('id'))
                                    ->where('status', 'pending')
                                    ->count(),
        ];
    }

    private function getFreelancerStats(User $user): array
    {
        return [
            'total_offers'      => $user->offers()->count(),
            'accepted_offers'   => $user->offers()->where('status', 'accepted')->count(),
            'active_jobs'       => $user->offers()
                                    ->where('status', 'accepted')
                                    ->whereHas('project', fn($q) => $q->where('status', 'in_progress'))
                                    ->count(),
            'total_earnings'    => $user->offers()->where('status', 'accepted')->sum('amount'),
            'avg_offer_amount'  => round($user->offers()->avg('amount'), 2),
        ];
    }
}