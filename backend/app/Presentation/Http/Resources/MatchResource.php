<?php

namespace App\Presentation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MatchResource extends JsonResource
{
    public function toArray($request)
    {
        $baseData = [
            'id' => $this->resource->id,
            'home_team' => $this->when($this->homeTeam, [
                'id' => $this->homeTeam->id,
                'name' => $this->homeTeam->name
            ]),
            'away_team' => $this->when($this->awayTeam, [
                'id' => $this->awayTeam->id,
                'name' => $this->awayTeam->name
            ]),            
            'home_score' => $this->resource->home_score,
            'away_score' => $this->resource->away_score,
            'status' => $this->resource->status,
            'played_at' => $this->resource->played_at,
        ];
        if (isset($this->resource->home_team) || isset($this->resource->away_team)) {
            $baseData['home_team'] = isset($this->resource->home_team) 
                ? new TeamResource($this->resource->home_team)
                : null;
                
            $baseData['away_team'] = isset($this->resource->away_team)
                ? new TeamResource($this->resource->away_team)
                : null;
        }
        return $baseData;
    }
}