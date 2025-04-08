<?php

namespace App\Domain\Project\Actions;
use App\Models\User;
use App\Domain\Project\DTOs\ProjectData;
use App\Domain\Project\Actions\CreateProjectAction;
use App\Models\Project;
class CreateDefaultProject
{
    public function execute(User $user): Project
    {
        if ($user->ownedProjects()->exists() || $user->projects()->exists()) {
            \Log::info("Usuário {$user->id} já possui projetos, não criando projeto padrão.");

            throw new \Exception("Usuário {$user->id} já possui projetos, não criando projeto padrão.");
        }

        $projectData = new ProjectData(
            name: "{$user->name} Projeto",
            description: "Projeto padrão criado para {$user->name}",
            ownerId: $user->id
        );
        $createProjectAction = new CreateProjectAction();
        $project = $createProjectAction->execute($projectData);

        $project->users()->attach($user->id);

         return $project;
    }
}