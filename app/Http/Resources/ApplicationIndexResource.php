<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'applications' => ApplicationResource::collection(
                $this->resource['applications']
            ),
            'current_event' => $this->resource['current_event'],
            'student' => $this->resource['student'],
        ];
    }
}
