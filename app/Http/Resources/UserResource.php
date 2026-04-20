<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
    return [
        'id'         => $this->id,
        'full_name'  => $this->first_name . ' ' . $this->last_name,       
        'email'      => $this->email,
        'role'       => $this->role,
       'city'       => $this->whenLoaded('city', fn() => $this->city->name), 
        'avatar_url' => $this->avatar ?? asset('images/default-avatar.png'),
        'created_at' => $this->created_at?->toFormattedDateString(),
        'token' => $this->when($this->token, $this->token),
    ];
}
    }

