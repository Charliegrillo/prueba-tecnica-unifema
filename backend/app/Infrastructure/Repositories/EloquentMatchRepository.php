<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\MatchRepositoryInterface;
use App\Models\MatchGame;
use Illuminate\Support\Collection;

class EloquentMatchRepository implements MatchRepositoryInterface
{
    public function all(array $filters = []): Collection
    {
        $query = MatchGame::with(['homeTeam', 'awayTeam']);
        
        // Filtro por 'played'
        if (isset($filters['played'])) {
            if ($filters['played'] === 'true') {
                // Partidos jugados: completados con scores
                $query->where('status', 'completed')
                      ->whereNotNull('home_score')
                      ->whereNotNull('away_score');
            } elseif ($filters['played'] === 'false') {
                // Partidos no jugados: scheduled o sin scores
                $query->where(function($q) {
                    $q->where('status', '!=', 'completed')
                      ->orWhereNull('home_score')
                      ->orWhereNull('away_score');
                });
            }
        }
        
        return $query->get();
    }

    public function find(int $id): ?MatchGame
    {
        return MatchGame::with(['homeTeam', 'awayTeam'])->find($id);
    }

    public function create(array $data): MatchGame
    {
        return MatchGame::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $match = MatchGame::find($id);
        
        if (!$match) {
            return false;
        }
        
        return $match->update($data);
    }

    public function getCompletedMatches(): Collection
    {
        return MatchGame::where('status', 'completed')
            ->whereNotNull('home_score')
            ->whereNotNull('away_score')
            ->get();
    }

    // Si necesitas otros métodos, agrégales aquí
    public function delete(int $id): bool
    {
        $match = MatchGame::find($id);
        
        if (!$match) {
            return false;
        }
        
        return $match->delete();
    }
}