<?php

namespace App\Presentation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    public function toArray($request)
    {
        if (is_object($this->resource)) {
            return [
                'id' => $this->resource->id,
                'name' => $this->resource->name,
                'goals_for' => $this->resource->goals_for ?? 0,
                'goals_against' => $this->resource->goals_against ?? 0,
            ];
        }
        return [
            'id' => $this->id,
            'founded_year' => $this->founded_year,
            'goals_for' => $this->goals_for ?? 0,
            'goals_against' => $this->goals_against ?? 0,
        ];
    }
}