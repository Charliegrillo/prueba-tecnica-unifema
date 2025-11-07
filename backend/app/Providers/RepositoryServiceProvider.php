<?php

namespace App\Providers;

use App\Domain\Repositories\MatchRepositoryInterface;
use App\Domain\Repositories\TeamRepositoryInterface;
use App\Infrastructure\Repositories\EloquentMatchRepository;
use App\Infrastructure\Repositories\EloquentTeamRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TeamRepositoryInterface::class, EloquentTeamRepository::class);
        $this->app->bind(MatchRepositoryInterface::class, EloquentMatchRepository::class);
    }
}