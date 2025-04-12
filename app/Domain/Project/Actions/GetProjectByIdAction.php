<?php

namespace App\Domain\Project\Actions;

use App\Domain\Project\Models\Project;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class GetProjectByIdAction
{
    public static function execute(int $projectId): Project{
        if ($projectId === 0) {
           throw new InvalidParameterException("Project ID cannot be 0");
        }

        $project = Project::query()->find($projectId);

        if (! $project) {
            throw new \DomainException("Project not Found");
        }
        return $project;
    }

}
