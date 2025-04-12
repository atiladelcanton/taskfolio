<?php

declare(strict_types=1);

namespace App\Livewire\Projects;

use App\Domain\Board\Events\CreateDefaultBoard;
use App\Domain\Project\Actions\{AddUserInProjectAction,
    DeleteProjectAction,
    GetProjectByIdAction,
    GetProjectsAndSearchableAction,
    GetUsersInMyProjectsAction,
    GetUsersInSpecificProjectAction,
    RemoveParticipantToProjectAction
};
use App\Domain\Project\Models\Project as ProjectModel;
use App\Livewire\Forms\ProjectForm;
use DomainException;
use Flux\Flux;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Application;
use Livewire\Attributes\{Layout, Title};
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Gerenciamento de Projetos')]
class Project extends Component
{
    public ProjectForm $projectForm;

    public string $searchTerm = '';

    /**
     * @var array<int>
     */
    public array $syncParticipants = [];

    public string|int $projectId = 0;

    public object $usersInSomeProjects;
    /**
     * @var array<int, array<string, mixed>>
     */
    public array $usersInProject = [];

    public function render(): View|Application|Factory|\Illuminate\View\View
    {
        $projects = GetProjectsAndSearchableAction::execute($this->searchTerm);

        return view('livewire.projects.project', ['projects' => $projects]);
    }

    public function createProject(): null
    {
        $project = $this->projectForm->store();
        $this->projectForm->reset();

        Flux::toast(text: 'Projeto criado com sucesso!', heading: 'Sucesso', variant: 'success');

        return $this->redirect(route('projects.index'), navigate: true);
    }

    public function editProject(string $id): void
    {
        $this->projectId = (int)$id;
        $project = ProjectModel::find($id);
        $this->projectForm->fill($project);
        self::modal('new-project')->show();
    }

    public function updateProject(): void
    {
        $project = GetProjectByIdAction::execute($this->projectId);

        $this->projectForm->update($project);
        $this->projectForm->reset();
        self::modal('new-project')->close();
        Flux::toast(text: 'Projeto atualizado com sucesso!', heading: 'Sucesso', variant: 'success');
    }

    public function deleteProject(): null
    {
        DeleteProjectAction::execute($this->projectId);
        Flux::toast(text: 'Projeto deletado com sucesso!', heading: 'Sucesso', variant: 'success');
        $this->projectId = 0;


        return $this->redirect(route('projects.index'), navigate: true);
    }

    public function confirmDeleteProject(string $id): void
    {
        $this->projectId = (int)$id;
        self::modal('delete-project')->show();
    }

    public function addParticipants(string $id): void
    {
        $this->projectId = (int)$id;

        $this->usersInSomeProjects = GetUsersInMyProjectsAction::execute();

        $this->usersInProject = GetUsersInSpecificProjectAction::execute($this->projectId);
        /**
         * @todo criar um event e listener apos adicionar um participante ao projeto, para notificar
         *   -> PartcipantAddedToProjectListener
         */
        self::modal('add-participants-project')->show();
    }

    public function closeModal(): void
    {
        $this->projectId = 0;
    }

    public function syncParticipantsToProject(): void
    {

        AddUserInProjectAction::execute($this->projectId, $this->syncParticipants, auth()->id());
        $this->syncParticipants = [];
        self::modal('add-participants-project')->close();

        Flux::toast(text: 'Participantes adicionados com sucesso!', heading: 'Sucesso', variant: 'success');
    }

    public function removeParticipantFromProject(int $userId): void
    {
        try {
            RemoveParticipantToProjectAction::execute($this->projectId, $userId);
            $this->usersInProject = GetUsersInSpecificProjectAction::execute($this->projectId);
            $this->usersInSomeProjects = GetUsersInMyProjectsAction::execute();
            Flux::toast(text: 'Participante removido com sucesso', heading: 'Sucesso', variant: 'success');
        } catch (DomainException $e) {
            Flux::toast(text: $e->getMessage(), heading: 'Erro', variant: 'danger');
        }
    }
}
