<?php

declare(strict_types=1);

namespace App\Domain\User\Listeners;

use App\Domain\Project\Actions\CreateProjectAction;
use App\Domain\Project\DTOs\ProjectData;
use App\Domain\User\Events\EmailVerified;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateDefaultProjectAfterVerification implements ShouldQueue
{
    protected $createProjectAction;

    public function __construct(CreateProjectAction $createProjectAction)
    {
        $this->createProjectAction = $createProjectAction;
    }

    /**
     * Handle the event.
     */
    public function handle(EmailVerified $event): void
    {
        $user = $event->user;

        // If the user already has projects, do not create a default project
        if ($user->ownedProjects()->exists() || $user->projects()->exists()) {
            \Log::info("Usuário {$user->id} já possui projetos, não criando projeto padrão.");

            return;
        }

        // Create a default project for the user
        $projectData = new ProjectData(
            name: "{$user->name} Projeto",
            description: "Projeto padrão criado para {$user->name}",
            ownerId: $user->id
        );

        $project = $this->createProjectAction->execute($projectData);

        // Attach the user to the project
        $project->users()->attach($user->id);

        \Log::info("Projeto padrão criado com sucesso para o usuário {$user->id}");
    }
}
