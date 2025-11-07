<?php

namespace App\Domain\Repositories;

use App\Models\MatchGame;
use Illuminate\Support\Collection;

interface MatchRepositoryInterface
{
    public function all(array $filters = []): Collection;
    public function find(int $id): ?MatchGame; // Cambiar a App\Models\MatchGame
    public function create(array $data): MatchGame;
    public function update(int $id, array $data): bool;
    public function getCompletedMatches(): Collection;
}