<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use App\Models\MatchGame;
use App\Application\Services\StandingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StandingsCalculationTest extends TestCase
{
    use RefreshDatabase;

    private StandingsService $standingsService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->standingsService = app(StandingsService::class);
    }

    public function test_standings_calculation_with_victory_and_draw(): void
    {
        // Crear equipos en la base de datos
        $teamA = Team::create(['name' => 'Team A']);
        $teamB = Team::create(['name' => 'Team B']);

        // Crear partidos COMPLETADOS con scores
        MatchGame::create([
            'home_team_id' => $teamA->id,
            'away_team_id' => $teamB->id,
            'home_score' => 3,   // Victoria para Team A
            'away_score' => 1,
            'status' => 'completed',
            'played_at' => now(),
        ]);

        MatchGame::create([
            'home_team_id' => $teamB->id,
            'away_team_id' => $teamA->id,
            'home_score' => 2,   // Empate
            'away_score' => 2,
            'status' => 'completed', 
            'played_at' => now(),
        ]);

        // Ejecutar cálculo
        $standings = $this->standingsService->calculateStandings();

        // Debug: mostrar el standings completo
        dump('Full Standings:', $standings->toArray());

        // Verificar que tenemos standings para ambos equipos
        $this->assertCount(2, $standings, 'Should have standings for 2 teams');

        // Encontrar los standings por nombre del equipo (más robusto)
        $teamAStanding = $standings->firstWhere('team_name', 'Team A');
        $teamBStanding = $standings->firstWhere('team_name', 'Team B');

        $this->assertNotNull($teamAStanding, 'Team A should be in standings');
        $this->assertNotNull($teamBStanding, 'Team B should be in standings');

        // Verificar Team A (1 victoria + 1 empate)
        $this->assertEquals(2, $teamAStanding['played'], 'Team A should have 2 matches played');
        $this->assertEquals(1, $teamAStanding['won'], 'Team A should have 1 win');
        $this->assertEquals(1, $teamAStanding['drawn'], 'Team A should have 1 draw');
        $this->assertEquals(0, $teamAStanding['lost'], 'Team A should have 0 losses');
        $this->assertEquals(5, $teamAStanding['goals_for'], 'Team A should have 5 goals for (3 + 2)');
        $this->assertEquals(3, $teamAStanding['goals_against'], 'Team A should have 3 goals against (1 + 2)');
        $this->assertEquals(2, $teamAStanding['goal_difference'], 'Team A should have goal difference of 2');
        $this->assertEquals(4, $teamAStanding['points'], 'Team A should have 4 points (3 for win + 1 for draw)');

        // Verificar Team B (1 derrota + 1 empate)
        $this->assertEquals(2, $teamBStanding['played'], 'Team B should have 2 matches played');
        $this->assertEquals(0, $teamBStanding['won'], 'Team B should have 0 wins');
        $this->assertEquals(1, $teamBStanding['drawn'], 'Team B should have 1 draw');
        $this->assertEquals(1, $teamBStanding['lost'], 'Team B should have 1 loss');
        $this->assertEquals(3, $teamBStanding['goals_for'], 'Team B should have 3 goals for (1 + 2)');
        $this->assertEquals(5, $teamBStanding['goals_against'], 'Team B should have 5 goals against (3 + 2)');
        $this->assertEquals(-2, $teamBStanding['goal_difference'], 'Team B should have goal difference of -2');
        $this->assertEquals(1, $teamBStanding['points'], 'Team B should have 1 point (0 for wins + 1 for draw)');

        // Verificar ordenamiento (Team A primero por tener más puntos)
        $this->assertEquals('Team A', $standings[0]['team_name'], 'Team A should be first with more points');
        $this->assertEquals('Team B', $standings[1]['team_name'], 'Team B should be second with fewer points');
    }

    public function test_standings_ordering_criteria_points_gd_goals(): void
    {
        // Crear equipos
        $team1 = Team::create(['name' => 'Team High Points']);
        $team2 = Team::create(['name' => 'Team Same Points Better GD']);
        $team3 = Team::create(['name' => 'Team Same Points Worse GD']); 
        $team4 = Team::create(['name' => 'Team Low Points']);

        // Crear partidos que generen diferentes estadísticas
        // Team 1: 2 victorias = 6 puntos
        MatchGame::create([
            'home_team_id' => $team1->id, 'away_team_id' => $team2->id,
            'home_score' => 2, 'away_score' => 0, 'status' => 'completed'
        ]);
        MatchGame::create([
            'home_team_id' => $team1->id, 'away_team_id' => $team3->id, 
            'home_score' => 2, 'away_score' => 0, 'status' => 'completed'
        ]);

        // Team 2: 1 victoria = 3 puntos, GD +3
        MatchGame::create([
            'home_team_id' => $team2->id, 'away_team_id' => $team4->id,
            'home_score' => 3, 'away_score' => 0, 'status' => 'completed'
        ]);

        // Team 3: 1 victoria = 3 puntos, GD +1  
        MatchGame::create([
            'home_team_id' => $team3->id, 'away_team_id' => $team4->id,
            'home_score' => 1, 'away_score' => 0, 'status' => 'completed'
        ]);

        // Team 4: 0 puntos
        MatchGame::create([
            'home_team_id' => $team4->id, 'away_team_id' => $team1->id,
            'home_score' => 0, 'away_score' => 2, 'status' => 'completed'
        ]);

        $standings = $this->standingsService->calculateStandings();

        // Debug
        dump('Ordering Test Standings:', $standings->toArray());

        // Verificar orden: puntos DESC, goal_diff DESC, goals_for DESC
        $this->assertEquals('Team High Points', $standings[0]['team_name'], 'Team 1 should be first (most points)');
        $this->assertEquals('Team Same Points Better GD', $standings[1]['team_name'], 'Team 2 should be second (same points as team 3 but better GD)');
        $this->assertEquals('Team Same Points Worse GD', $standings[2]['team_name'], 'Team 3 should be third (same points as team 2 but worse GD)');
        $this->assertEquals('Team Low Points', $standings[3]['team_name'], 'Team 4 should be last (least points)');
    }

    public function test_only_completed_matches_are_counted(): void
    {
        $teamA = Team::create(['name' => 'Team A']);
        $teamB = Team::create(['name' => 'Team B']);

        // Partido completado
        MatchGame::create([
            'home_team_id' => $teamA->id, 'away_team_id' => $teamB->id,
            'home_score' => 2, 'away_score' => 1, 'status' => 'completed'
        ]);

        // Partido no completado (no debería contar)
        MatchGame::create([
            'home_team_id' => $teamB->id, 'away_team_id' => $teamA->id,
            'home_score' => null, 'away_score' => null, 'status' => 'scheduled'
        ]);

        $standings = $this->standingsService->calculateStandings();

        $teamAStanding = $standings->firstWhere('team_name', 'Team A');
        $teamBStanding = $standings->firstWhere('team_name', 'Team B');

        // Solo debe contar 1 partido por equipo
        $this->assertEquals(1, $teamAStanding['played']);
        $this->assertEquals(1, $teamBStanding['played']);
    }
}