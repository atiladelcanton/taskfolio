<?php

declare(strict_types=1);

namespace App\Domain\Project\Actions;

use App\Domain\Project\DTOs\ProjectData;
use App\Domain\Project\Models\Project;
use App\Models\{User};

class CreateDefaultProjectAction
{
    public function execute(User $user): Project
    {
        if ($user->ownedProjects()->exists() || $user->projects()->exists()) {
            throw new \Exception("Usuário {$user->id} já possui projetos, não criando projeto padrão.");
        }

        $projectData = new ProjectData(
            name: "{$user->name} Projeto",
            description: "Projeto padrão criado para {$user->name}",
            ownerId: $user->id
        );
        $createProjectAction = new CreateProjectAction;
        $project = $createProjectAction->execute($projectData);

        $project->users()->attach($user->id);

        return $project;
    }
}
