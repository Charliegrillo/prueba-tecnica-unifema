<?php

namespace App\Application\Services;

use App\Domain\Repositories\MatchRepositoryInterface;
use App\Domain\Repositories\TeamRepositoryInterface;
use Illuminate\Support\Collection;

class StandingsService
{
    public function __construct(
        private TeamRepositoryInterface $teamRepository,
        private MatchRepositoryInterface $matchRepository
    ) {}

    public function calculateStandings(): Collection
    {
        $teams = $this->teamRepository->all();
        $completedMatches = $this->matchRepository->getCompletedMatches();
        
        $standings = collect();

        foreach ($teams as $team) {
            $stats = $this->calculateTeamStats($team, $completedMatches);
            $standings->push($stats);
        }

        return $this->sortStandings($standings);
    }

    private function calculateTeamStats($team, Collection $matches): array
    {
        $teamMatches = $matches->filter(function($match) use ($team) {
            return $match->home_team_id == $team->id || $match->away_team_id == $team->id;
        });

        $stats = [
            'team_id' => $team->id,
            'team_name' => $team->name,
            'played' => 0,
            'won' => 0,
            'drawn' => 0,
            'lost' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
        ];

        foreach ($teamMatches as $match) {
            $this->processMatch($match, $team->id, $stats);
        }

        $stats['goal_difference'] = $stats['goals_for'] - $stats['goals_against'];
        $stats['points'] = ($stats['won'] * 3) + $stats['drawn'];

        return $stats;
    }

    private function processMatch($match, int $teamId, array &$stats): void
    {
        $stats['played']++;

        if ($match->home_team_id == $teamId) {
            $goalsFor = $match->home_team_score;
            $goalsAgainst = $match->away_team_score;
        } else {
            $goalsFor = $match->away_team_score;
            $goalsAgainst = $match->home_team_score;
        }

        $stats['goals_for'] += $goalsFor;
        $stats['goals_against'] += $goalsAgainst;

        if ($goalsFor > $goalsAgainst) {
            $stats['won']++;
        } elseif ($goalsFor == $goalsAgainst) {
            $stats['drawn']++;
        } else {
            $stats['lost']++;
        }
    }

    private function sortStandings(Collection $standings): Collection
    {
        return $standings->sortByDesc('points')
                        ->sortByDesc('goal_difference')
                        ->values()
                        ->map(function ($stats, $index) {
                            $stats['position'] = $index + 1;
                            return $stats;
                        });
    }
}