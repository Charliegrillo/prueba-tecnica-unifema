<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Application\Services\StandingsService;
use Illuminate\Http\JsonResponse;

class StandingsController  extends Controller
{
    public function __construct(private StandingsService $standingsService) {}

    public function index(): JsonResponse
    {
        $standings = $this->standingsService->calculateStandings();
        return response()->json($standings);
    }
}