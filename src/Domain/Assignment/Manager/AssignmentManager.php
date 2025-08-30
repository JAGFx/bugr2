<?php

namespace App\Domain\Assignment\Manager;

use App\Domain\Assignment\Entity\Assignment;
use App\Domain\Assignment\Repository\AssignmentRepository;

class AssignmentManager
{
    public function __construct(
        private readonly AssignmentRepository $repository,
    ) {
    }

    /**
     * @return Assignment[]
     */
    public function getAssignments(): array
    {
        /** @var Assignment[] $assignments */
        $assignments = $this->repository->findAll();

        return $assignments;
    }

    public function create(Assignment $assignment): void
    {
        $this->repository
            ->create($assignment)
            ->flush();
    }

    public function update(): void
    {
        $this->repository->flush();
    }

    public function remove(Assignment $assignment): void
    {
        $this->repository
            ->remove($assignment)
            ->flush();
    }
}
