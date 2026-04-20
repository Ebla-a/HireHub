<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            
            'status'        => $this->status,
            
       
            'days_left'     => $this->getDaysLeft(),
            
           
            'offers_count'  => $this->whenCounted('offers'),

           
            'owner'   => new UserResource($this->whenLoaded('user')),

            'tags'    => TagResource::collection($this->whenLoaded('tags')),
            'budget'  => $this->formatBudget(),
            
            'created_at'    => $this->created_at->diffForHumans(),
            'attachments' => AttachmentResource::collection($this->whenLoaded('attachments')),
        ];
    }

    private function getDaysLeft(): string
    {
        $deadline = Carbon::parse($this->deadline);
        
        if ($deadline->isPast()) {
            return 'Expired';
        }

        $days = now()->diffInDays($deadline);
        return $days === 0 ? 'Due Today' : "$days Days left";
    }

    private function formatBudget(): string
    {
        $formatted = number_format($this->budget, 2);
        return $this->budget_type === 'hourly' ? "\${$formatted}/hr" : "\${$formatted}";
    }
}