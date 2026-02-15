<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AwardRegistrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'award_id' => $this->resource->award_id,
            'event_id' => $this->resource->event_id,
            'academic_year' => $this->resource->academic_year,
            'status' => $this->resource->status,
            'awardable_id' => $this->resource->awardable_id,     // เพิ่ม
            'awardable_type' => $this->resource->awardable_type,   // เพิ่ม
//            'documents',
        ];
    }
}
