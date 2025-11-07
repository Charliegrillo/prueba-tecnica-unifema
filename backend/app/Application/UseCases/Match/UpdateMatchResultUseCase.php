<?php

namespace App\Application\UseCases\Match;

use App\Application\DTOs\UpdateMatchResultDTO;
use App\Domain\Repositories\MatchRepositoryInterface;
use Illuminate\Validation\ValidationException;

class UpdateMatchResultUseCase
{
    public function __construct(
        private MatchRepositoryInterface $matchRepository
    ) {}

    public function execute(int $matchId, UpdateMatchResultDTO $dto)
    {
        $this->validate($dto);
        
        $updateData = [
            'home_score' => $dto->home_score,
            'away_score' => $dto->away_score,
            'status' => 'completed',
            'played_at' => now()->toDateTimeString()
        ];

        return $this->matchRepository->update($matchId, $updateData);
    }

    private function validate(UpdateMatchResultDTO $dto): void
    {
        $validator = validator((array) $dto, [
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}