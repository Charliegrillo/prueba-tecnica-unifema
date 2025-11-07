<?php

namespace App\Domain\Entities;

class MatchGame
{
    public function __construct(
        public ?int $id,
        public int $home_team_id,
        public int $away_team_id,
        public ?int $home_score = null,
        public ?int $away_score = null,
        public ?string $played_at = null,
        public string $status = 'scheduled'
    ) {}
}