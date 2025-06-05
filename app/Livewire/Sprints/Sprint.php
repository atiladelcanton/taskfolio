<?php

declare(strict_types=1);

namespace App\Livewire\Sprints;

use App\Domain\Project\Models\Project;
use App\Domain\Sprint\Actions\GetSprintsByProjectAction;
use App\Domain\Sprint\Actions\RemoveSprintAction;
use App\Domain\Sprint\Enums\SprintLivewireEvents;
use Flux\Flux;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Gerenciamento de Sprints')]
class Sprint extends Component
{
    public Project $project;

    public function mount(string $project_code): void
    {
        /**
         * @todo verificar se vai validar com usuário logado se ele pode ver a lista de sprint daquele projeto
         * possíbilidade de criar classe apenas para essa validação
         */
        $this->project = Project::query()->where('project_code', $project_code)->firstOrFail();
    }

    public function editSprint(int $sprintId): void
    {
        $this->dispatch(SprintLivewireEvents::EditModal->value, projectId: $this->project->id, sprintId: $sprintId);
        self::modal('modal-sprint')->show();
    }

    public function confirmDeleteSprint(int $sprintId): void
    {
        $this->dispatch(SprintLivewireEvents::Remove->value, sprintId: $sprintId);
        self::modal('delete-sprint')->show();
    }

    public function render(): Factory|Application|View|\Illuminate\View\View
    {
        $sprints = GetSprintsByProjectAction::execute($this->project->id);

        return view('livewire.sprints.sprints', ['sprints' => $sprints]);
    }

    public function closeModalForm(): void
    {
        $this->dispatch(SprintLivewireEvents::ClearModal->value);
    }
}
