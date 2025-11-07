<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Application\DTOs\UpdateMatchResultDTO;
use App\Application\UseCases\Match\UpdateMatchResultUseCase;
use App\Domain\Repositories\MatchRepositoryInterface;
use App\Presentation\Http\Resources\MatchResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MatchController extends Controller
{
    public function __construct(
        private MatchRepositoryInterface $matchRepository,
        private UpdateMatchResultUseCase $updateMatchResultUseCase
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = [];
        
        if ($request->has('played')) {
            $filters['played'] = $request->played;
        }

        $matches = $this->matchRepository->all($filters);
        return response()->json(MatchResource::collection($matches));
    }

    public function updateResult(Request $request, $id): JsonResponse
    {
        try {
            $dto = UpdateMatchResultDTO::fromArray($request->all());
            $this->updateMatchResultUseCase->execute($id, $dto);
            $updatedMatch = $this->matchRepository->find($id);
            return response()->json(new MatchResource($updatedMatch));
            
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }
}