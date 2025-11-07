<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Team;
use App\Models\MatchGame;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('match_games')->delete();
        DB::table('teams')->delete();
        DB::table('users')->delete();

        // Crear equipos
        $teams = collect(['Dragons', 'Sharks', 'Tigers', 'Wolves'])
            ->map(function($name) {
                return \App\Models\Team::create(['name' => $name]);
            });

        // Crear partidos
        \App\Models\MatchGame::create([
            'home_team_id'=>$teams[0]->id, 'away_team_id'=>$teams[1]->id
        ]);
        \App\Models\MatchGame::create([
           'home_team_id'=>$teams[2]->id, 'away_team_id'=>$teams[3]->id
        ]);
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
