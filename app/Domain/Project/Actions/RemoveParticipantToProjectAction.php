<?php

declare(strict_types=1);

namespace App\Domain\Project\Actions;

use App\Domain\Project\Models\Project;
use App\Models\UserProject;
use DomainException;

class RemoveParticipantToProjectAction
{
    /**
     * @param  int  $projectId  The ID of the project
     * @param  int  $userId  The ID of the user to be removed
     *
     * @throw DomainException
     */
    public static function execute(int $projectId, int $userId): void
    {
        $project = Project::query()->find($projectId);

        if ($project === null) {
            throw new DomainException('Projeto não encontrado');
        }

        if ($project->owner_id === $userId) {
            throw new DomainException('Não é possível remover o proprietário do projeto');
        }

        if ($project->owner_id !== auth()->id()) {
            throw new DomainException('Você não tem permissão para remover participantes');
        }
        $pivotEntry = UserProject::query()->where('project_id', $projectId)->where('user_id', $userId)->first();

        if ($pivotEntry) {
            $pivotEntry->delete();
        }
    }
}
