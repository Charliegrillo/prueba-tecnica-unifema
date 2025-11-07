<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchGame extends Model
{
    use HasFactory;
    
    protected $table = 'match_games';

    protected $fillable = [
        'home_team_id', 
        'away_team_id', 
        'home_score', 
        'away_score',
        'played_at',
        'status'
    ];

    protected $attributes = [
        'status' => 'scheduled',
    ];


    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }
}