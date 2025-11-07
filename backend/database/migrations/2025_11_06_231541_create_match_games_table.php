<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_team_id')->constrained('teams');
            $table->foreignId('away_team_id')->constrained('teams');
            $table->integer('home_score')->nullable();
            $table->integer('away_score')->nullable();
            $table->dateTime('played_at')->nullable();
            $table->timestamps();
            $table->string('status')->default('scheduled');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_games');
    }
};