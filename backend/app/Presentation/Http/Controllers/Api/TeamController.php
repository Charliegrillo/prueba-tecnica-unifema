<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\DTOs\CreateTeamDTO;
use App\Application\UseCases\Team\CreateTeamUseCase;
use App\Domain\Repositories\TeamRepositoryInterface;
use App\Presentation\Http\Resources\TeamResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TeamController extends Controller
{
    public function __construct(
        private TeamRepositoryInterface $teamRepository,
        private CreateTeamUseCase $createTeamUseCase
    ) {}

    public function index(): JsonResponse
    {
        $teams = $this->teamRepository->all();
        return response()->json(TeamResource::collection($teams));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $dto = CreateTeamDTO::fromArray($request->all());
            $team = $this->createTeamUseCase->execute($dto);
            
            return response()->json(new TeamResource($team), 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal server error'
            ], 500);
        }
    }
}