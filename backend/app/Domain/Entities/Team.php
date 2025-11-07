<?php

namespace App\Domain\Entities;

class Team
{
    public function __construct(
        public ?int $id,
        public string $name,
        public ?string $city = null,
        public ?int $founded_year = null,
        public int $goals_for = 0,
        public int $goals_against = 0
    ) {}
}