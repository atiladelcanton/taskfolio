<?php

namespace App\Domain\Project\Actions;

class DeleteProjectAction
{
    public static function execute(int $projectId): void {
        $project = GetPRojectByIdAction::execute($projectId);

        $project->delete();
    }
}
