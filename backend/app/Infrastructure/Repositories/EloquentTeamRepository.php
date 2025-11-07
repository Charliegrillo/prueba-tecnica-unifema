<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Team;
use App\Domain\Repositories\TeamRepositoryInterface;
use App\Models\Team as TeamModel;
use Illuminate\Support\Collection;

class EloquentTeamRepository implements TeamRepositoryInterface
{
    public function all(): Collection
    {
        return TeamModel::all()->map(function ($team) {
            return new Team(
                $team->id,
                $team->name,
                $team->city,
                $team->founded_year,
                $team->goals_for,
                $team->goals_against
            );
        });
    }

    public function find(int $id): ?Team
    {
        $team = TeamModel::find($id);
        
        if (!$team) return null;

        return new Team(
            $team->id,
            $team->name,
            $team->city,
            $team->founded_year,
            $team->goals_for,
            $team->goals_against
        );
    }

    public function create(array $data): Team
    {
        $team = TeamModel::create($data);
        
        return new Team(
            $team->id,
            $team->name,
            $team->city,
            $team->founded_year,
            $team->goals_for,
            $team->goals_against
        );
    }

    public function update(int $id, array $data): bool
    {
        return TeamModel::where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return TeamModel::destroy($id);
    }
}