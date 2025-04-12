<?php

declare(strict_types=1);

namespace App\Domain\Project\Actions;

use App\Domain\Project\Models\Project;
use DomainException;

class GetUsersInSpecificProjectAction
{
    /**
     * @param  int  $projectId  The ID of the project to check
     * @return array<int, array<string, mixed>> Array of user data
     *
     * @throws DomainException When project is not found
     */
    public static function execute(int $projectId): array
    {
        $project = Project::query()->find($projectId);
        if (is_null($project)) {
            throw new DomainException('Project not found GetUsersInSpecificProjectAction');
        }

        return $project->users()->select('users.id as user_id', 'users.name', 'users.avatar')->get()->toArray();
    }
}
