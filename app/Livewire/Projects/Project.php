<?php

declare(strict_types=1);

namespace App\Livewire\Projects;

use App\Livewire\Forms\ProjectForm;
use App\Models\Project as ProjectModel;
use Auth;
use Flux\Flux;
use Livewire\Attributes\{Layout, Title};
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Gerenciamento de Projetos')]
class Project extends Component
{
    public ProjectForm $projectForm;

    public $searchTerm = '';

    public $projectId = 0;

    public function render()
    {
        $projects = ProjectModel::query()
            ->when($this->searchTerm, function ($q) {
                return $q->where('name', 'like', '%'.$this->searchTerm.'%')
                    ->orWhere('project_code', 'like', '%'.$this->searchTerm.'%')
                    ->orWhere('description', 'like', '%'.$this->searchTerm.'%');
            })->where(function ($q) {
                $q->where('owner_id', Auth::id())
                    ->orWhereHas('users', function ($q) {
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
        // @todo Incluir fluxo de criacao de boards
        Flux::toast(text: 'Projeto criado com sucesso!', heading: 'Sucesso', variant: 'success');

        return $this->redirect(route('projects.index'), navigate: true);
    }

    public function editProject(string $id)
    {
        $this->projectId = (int) $id;
        $project = ProjectModel::find($id);
        $this->projectForm->fill($project);
        $this->modal('new-project')->show();
    }

    public function updateProject()
    {
        $project = ProjectModel::find($this->projectId);
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
}
