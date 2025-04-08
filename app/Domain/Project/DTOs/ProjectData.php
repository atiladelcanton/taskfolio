<?php

declare(strict_types=1);

namespace App\Domain\Project\DTOs;

readonly class ProjectData
{
    public function __construct(
        public string $name,
        public string $description,
        public int $ownerId,
        public ?string $projectCode = null
    ) {}

    public static function from(array $data): self
    {
        return new self(...$data);
    }

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
