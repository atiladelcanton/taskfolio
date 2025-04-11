<?php

declare(strict_types=1);

namespace App\Livewire\Projects;

use App\Domain\Board\Events\CreateDefaultBoard;
use App\Livewire\Forms\ProjectForm;
use App\Models\Project as ProjectModel;
use App\Models\User;
use Auth;
use Flux\Flux;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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
     * @var array<int> $syncParticipants
     */
    public array $syncParticipants = [];
    public string|int $projectId = 0;
public object $usersInMyProject;
    public function render(): View|Application|Factory|\Illuminate\View\View
    {
        $projects = ProjectModel::query()
            ->when($this->searchTerm, fn($q) => $q->where('name', 'like', '%'.$this->searchTerm.'%')
                ->orWhere('project_code', 'like', '%'.$this->searchTerm.'%')
                ->orWhere('description', 'like', '%'.$this->searchTerm.'%'))->where(function ($q): void {
                $q->where('owner_id', Auth::id())
                    ->orWhereHas('users', function ($q): void {
                        $q->where('users.id', Auth::id());
                    });
            })->with(['owner', 'users', 'sprints'])->latest()->paginate(10);

        return view('livewire.projects.project', [
            'projects' => $projects,
        ]);
    }

    public function createProject(): null
    {
        $project = $this->projectForm->store();
        $this->projectForm->reset();
        event(new CreateDefaultBoard($project));
        Flux::toast(text: 'Projeto criado com sucesso!', heading: 'Sucesso', variant: 'success');

        return $this->redirect(route('projects.index'), navigate: true);
    }

    public function editProject(string $id): void
    {
        $this->projectId = (int) $id;
        $project = ProjectModel::find($id);
        $this->projectForm->fill($project);
        self::modal('new-project')->show();
    }

    public function updateProject(): void
    {


        if (empty($this->projectId) || $this->projectId == 0) {
            Flux::toast(text: 'ID do projeto inválido!', heading: 'Erro', variant: 'danger');
            return;
        }

        $project = ProjectModel::find($this->projectId);

        if (!$project) {
            \Log::warning('Projeto não encontrado no updateProject', [
                'project_id' => $this->projectId
            ]);
            Flux::toast(text: 'Projeto não encontrado!', heading: 'Erro', variant: 'danger');
            return;
        }

        $this->projectForm->update($project);
        $this->projectForm->reset();
        $this->modal('new-project')->close();
        Flux::toast(text: 'Projeto atualizado com sucesso!', heading: 'Sucesso', variant: 'success');
    }

    public function deleteProject(): null
    {
        ProjectModel::destroy($this->projectId);
        Flux::toast(text: 'Projeto deletado com sucesso!', heading: 'Sucesso', variant: 'success');
        $this->projectId = 0;

        return $this->redirect(route('projects.index'), navigate: true);
    }

    public function confirmDeleteProject(string $id): void
    {
        $this->projectId = (int) $id;
        $this->modal('delete-project')->show();
    }

    public function  addParticipants(string $id): void{
        $this->projectId = (int) $id;
        $this->usersInMyProject =  User::whereHas('projects', function($query) {
            $query->where('owner_id', auth()->id());
        })
            ->select('id as user_id', 'name', 'avatar')
            ->distinct()
            ->get();
        $this->modal('add-participants-project')->show();
    }

    public function closeModal(): void
    {
        $this->projectId = 0;
    }

    public function syncParticipantsToProject(): void {


        $project = ProjectModel::findOrFail($this->projectId);

        $existingUserIds = $project->users()->pluck('users.id')->toArray();

        $newUserIds = array_diff($this->syncParticipants, $existingUserIds);

        if (empty($newUserIds)) {
            Flux::toast(text: 'Todos os participantes selecionados já estão no projeto', heading: 'Informação', variant: 'info');
        } else {
            $project->users()->attach($newUserIds);
            Flux::toast(text: 'Participantes adicionados com sucesso!', heading: 'Sucesso', variant: 'success');
        }

        $this->syncParticipants = [];

        self::modal('add-participants-project')->close();

        $savedProjectId = $this->projectId;

        $this->dispatch('participants-synced', $savedProjectId);


    }
}
