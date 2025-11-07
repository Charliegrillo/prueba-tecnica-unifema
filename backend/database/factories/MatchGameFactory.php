<?php

namespace Database\Factories;

use App\Models\MatchGame;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatchGameFactory extends Factory
{
    protected $model = MatchGame::class;

    public function definition(): array
    {
        return [
            'home_team_id' => Team::factory(),
            'away_team_id' => Team::factory(),
            'match_date' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'home_score' => null,
            'away_score' => null,
            'status' => 'scheduled',
            'played' => false,
            'played_at' => null,
        ];
    }

    public function completed(int $homeScore, int $awayScore): self
    {
        return $this->state([
            'home_score' => $homeScore,
            'away_score' => $awayScore,
            'status' => 'completed',
            'played' => true,
            'played_at' => now(),
        ]);
    }
}