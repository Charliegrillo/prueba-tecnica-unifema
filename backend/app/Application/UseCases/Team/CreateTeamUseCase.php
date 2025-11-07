<?php

namespace App\Application\UseCases\Team;

use App\Application\DTOs\CreateTeamDTO;
use App\Domain\Repositories\TeamRepositoryInterface;
use Illuminate\Validation\ValidationException;

class CreateTeamUseCase
{
    public function __construct(
        private TeamRepositoryInterface $teamRepository
    ) {}

    public function execute(CreateTeamDTO $dto)
    {
        $this->validate($dto);
        return $this->teamRepository->create([
            'name' => $dto->name
        ]);
    }

    private function validate(CreateTeamDTO $dto): void
    {
        $validator = validator((array) $dto, [
            'name' => 'required|string|max:255|unique:teams'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}