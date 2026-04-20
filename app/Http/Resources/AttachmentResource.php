<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        'id'        => $this->id,
        'name'      => $this->file_name,
        'url'       => Storage::url($this->file_path), 
        'size'      => round($this->file_size / 1024, 2) . ' KB',
        'type'      => $this->file_type,
    ];
    }
}
