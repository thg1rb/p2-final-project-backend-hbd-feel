<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'application_id' => $this->resource->id,
            'event_id' => $this->resource->event_id,
            'academic_year' => $this->resource->event->academic_year,
            'semester' => $this->resource->event->semester,
            'status' => $this->resource->status,
            'path' => $this->resource->path,
            'level' => $this->resource->level,
            'grade' => $this->resource->grade,
            'document' => $this->resource->document,
            'year' => $this->resource->year,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'award_name' => $this->resource->award->name,
            'form_path' => $this->resource->award->form_path,
            'requirements' => $this->resource->requirements,
        ];
    }
}
