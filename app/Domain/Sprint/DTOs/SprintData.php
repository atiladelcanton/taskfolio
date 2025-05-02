<?php

namespace App\Domain\Sprint\DTOs;

use App\Domain\Sprint\Enums\SprintStatus;
use Carbon\CarbonInterface;

readonly class SprintData
{
    public function __construct(
        public int $projectId,
        public string $name,
        public CarbonInterface $startDate,
        public CarbonInterface $endDate,
        public SprintStatus $status,
    )
    {
    }

    /**
     * @param array $data
     * @return self
     */
    public static function from(array $data): self
    {
        return new self(
            $data['projectId'],
            $data['name'],
            $data['startDate'],
            $data['endDate'],
            $data['status']
        );
    }
}
