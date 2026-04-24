<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        'id'              => $this->id,
         'user_id'         => $this->user_id,
        'amount'          => '$' . number_format($this->amount, 2),
        'delivery_days'   => $this->delivery_days . ' day',
        'proposal_letter' => $this->proposal_letter,
        'status'          => $this->status,
        'freelancer'      => new UserResource($this->whenLoaded('user')),
        'created_at'      => $this->created_at->diffForHumans(),
    ];
    }
}



