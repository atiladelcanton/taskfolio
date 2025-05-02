<?php

namespace App\Livewire\Sprints;

use App\Domain\Project\Models\Project;
use App\Domain\Sprint\Enums\SprintLivewireEvents;
use Flux\Flux;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Livewire\Forms\SprintForm;
use App\Domain\Sprint\Models\Sprint;

class SprintFormComponent extends Component
{
    public SprintForm $sprintForm;

    public Project $project;
    public ?int $sprintId = null;

    public string $title = 'Nova Sprint';
    public string $btnSubmitText = 'Criar Sprint';

    public function mount(int $projectId, ?int $sprintId = null): void
    {
        $this->edit($projectId, $sprintId);
    }

    #[On(SprintLivewireEvents::EditModal->value)]
    public function edit(int $projectId, ?int $sprintId = null): void
    {
        $this->project = Project::query()->findOrFail($projectId);
        if ($sprintId) {
            $this->title = 'Editar Sprint';
            $this->btnSubmitText = 'Editar Sprint';
            $this->sprintId = $sprintId;

            $sprint = Sprint::query()->findOrFail($sprintId);
            $this->sprintForm->setSprint($sprint);
        }
    }

    #[On(SprintLivewireEvents::ClearModal->value)]
    public function clear(): void
    {
        $this->reset('sprintId', 'title', 'btnSubmitText');
        $this->resetValidation();
        $this->sprintForm->reset();
    }

    /**
     * @throws ValidationException
     */
    public function save(): void
    {
        if ($this->sprintId) {
            $message = 'Sprint atualizada com sucesso!';
            $this->sprintForm->update($this->project->id, $this->sprintId);
        } else {
            $message = 'Sprint cadastrada com sucesso!';
            $this->sprintForm->store($this->project->id);
        }

        Flux::toast(text: $message, heading: 'Sucesso', variant: 'success');

        $this->sprintForm->reset();

        $this->redirect(route('sprints.index', [$this->project->project_code]), navigate: true);
    }

    public function render()
    {
        return view('livewire.sprints.sprint-form-component');
    }
}
