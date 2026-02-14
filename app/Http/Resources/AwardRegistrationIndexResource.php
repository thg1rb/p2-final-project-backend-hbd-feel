<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AwardRegistrationIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'registrations' => AwardRegistrationResource::collection(
                $this->resource['registrations']
            ),
            'currentEvent' => $this->resource['currentEvent'],
            'allStats' => $this->resource['allStats'],
        ];
    }
}
