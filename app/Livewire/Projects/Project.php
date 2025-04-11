<?php

declare(strict_types=1);

namespace App\Livewire\Projects;

use App\Domain\Board\Events\CreateDefaultBoard;
use App\Livewire\Forms\ProjectForm;
use Illuminate\Support\Facades\DB;
use App\Models\{Project as ProjectModel, User, UserProject};
use Auth;
use Flux\Flux;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Application;
use Livewire\Attributes\{Layout, Title};
use Livewire\Component;
use Log;

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
    public array $usersInProject = [];
    public function render(): View|Application|Factory|\Illuminate\View\View
    {
        $projects = ProjectModel::query()
            ->when($this->searchTerm, fn ($q) => $q->where('name', 'like', '%'.$this->searchTerm.'%')
                ->orWhere('project_code', 'like', '%'.$this->searchTerm.'%')->orWhere('description', 'like', '%'.$this->searchTerm.'%'))
            ->where(function ($q): void {
            $q->where('owner_id', Auth::id())->orWhereHas('users', function ($q): void {
                $q->where('users.id', Auth::id());
            });
        })->with(['owner', 'users', 'sprints'])->latest()->paginate(10);

        return view('livewire.projects.project', ['projects' => $projects]);
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
        if ($this->projectId === '' || $this->projectId === '0' || $this->projectId == 0) {
            Flux::toast(text: 'ID do projeto inválido!', heading: 'Erro', variant: 'danger');

            return;
        }

        $project = ProjectModel::find($this->projectId);

        if (! $project) {
            Log::warning('Projeto não encontrado no updateProject', ['project_id' => $this->projectId]);
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

    public function addParticipants(string $id): void
    {
        $this->projectId = (int) $id;
        $project = ProjectModel::find($this->projectId);

        if (!$project) {
            Flux::toast(text: 'Projeto não encontrado!', heading: 'Erro', variant: 'danger');
            return;
        }
        $this->usersInSomeProjects = User::whereHas('projects', function ($query): void {
            $query->where('owner_id', auth()->id());
        })->select('id as user_id', 'name', 'avatar')->distinct()->get();

        $this->usersInProject = $project->users()
            ->select('users.id as user_id', 'users.name', 'users.avatar')
            ->get()->toArray();

        $this->modal('add-participants-project')->show();
    }

    public function closeModal(): void
    {
        $this->projectId = 0;
    }

    public function syncParticipantsToProject(): void
    {

        $project = ProjectModel::findOrFail($this->projectId);
        foreach ($this->syncParticipants as $userId) {

            $existingRecord = DB::table('user_projects')
                ->where('project_id', $this->projectId)
                ->where('user_id', $userId)
                ->first();

            if ($existingRecord) {

                DB::table('user_projects')
                    ->where('project_id', $this->projectId)
                    ->where('user_id', $userId)
                    ->update([
                        'deleted_at' => null,
                        'updated_at' => now()
                    ]);
            } else {
                $project->users()->attach($userId);
            }
        }
        $this->syncParticipants = [];
        $this->modal('add-participants-project')->close();

        Flux::toast(text: 'Participantes adicionados com sucesso!', heading: 'Sucesso', variant: 'success');
    }

    public function removeParticipantFromProject(int $userId): void
    {
        if (empty($this->projectId)) {
            Flux::toast(text: 'Nenhum projeto selecionado', heading: 'Erro', variant: 'danger');
            return;
        }

        $project = ProjectModel::find($this->projectId);

        if (!$project) {
            Flux::toast(text: 'Projeto não encontrado', heading: 'Erro', variant: 'danger');
            return;
        }

        if ($project->owner_id === $userId) {
            Flux::toast(text: 'Não é possível remover o proprietário do projeto', heading: 'Aviso', variant: 'warning');
            return;
        }

        if ($project->owner_id !== auth()->id()) {
            Flux::toast(text: 'Você não tem permissão para remover participantes deste projeto', heading: 'Erro', variant: 'danger');
            return;
        }

        $pivotEntry = UserProject::query()->where('project_id', $this->projectId)
            ->where('user_id', $userId)
            ->first();

        if ($pivotEntry) {
            $pivotEntry->delete();
        }

        $this->usersInProject = $project->users()
            ->select('users.id as user_id', 'users.name', 'users.avatar')
            ->get()->toArray();


        $this->usersInSomeProjects = User::whereHas('projects', static function($query) {
            $query->where('owner_id', auth()->id());
        })
            ->whereDoesntHave('projects', function($query) {
                $query->where('projects.id', $this->projectId);
            })
            ->select('id as user_id', 'name', 'avatar')
            ->distinct()
            ->get();

        Flux::toast(text: 'Participante removido com sucesso', heading: 'Sucesso', variant: 'success');
    }
}
