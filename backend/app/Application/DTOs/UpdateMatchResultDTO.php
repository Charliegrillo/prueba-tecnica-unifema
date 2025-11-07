<?php

namespace App\Application\DTOs;

class UpdateMatchResultDTO
{
    public function __construct(
        public int $home_score,
        public int $away_score
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['home_score'],
            $data['away_score']
        );
    }
}