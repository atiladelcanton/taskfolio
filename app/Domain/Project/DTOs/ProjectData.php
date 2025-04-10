<?php

declare(strict_types=1);

namespace App\Domain\Project\DTOs;

/**
 * @phpstan-type ProjectDataArray array{
 *     name: string,
 *     description: string,
 *     ownerId: int,
 *     projectCode?: string|null
 * }
 */
readonly class ProjectData
{
    public function __construct(
        public string $name,
        public string $description,
        public int $ownerId,
        public ?string $projectCode = null
    ) {}

    /**
     * @param  ProjectDataArray  $data
     */
    public static function from(array $data): self
    {
        return new self(
            $data['name'],
            $data['description'],
            $data['ownerId'],
            $data['projectCode'] ?? null
        );
    }

    /**
     * @return array{
     *     name: string,
     *     description: string,
     *     ownerId: int,
     *     projectCode: string|null
     * }
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'ownerId' => $this->ownerId,
            'projectCode' => $this->projectCode,
        ];
    }
}
