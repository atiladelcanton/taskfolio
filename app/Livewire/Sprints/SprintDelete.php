<?php

namespace App\Livewire\Sprints;

use App\Domain\Sprint\Actions\RemoveSprintAction;
use App\Domain\Sprint\Enums\SprintLivewireEvents;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class SprintDelete extends Component
{
    public int $sprintId;

    public function mount(bool $isConfirmation = true): void
    {

    }

    #[On(SprintLivewireEvents::Remove->value)]
    public function setSprint(int $sprintId): void
    {
        $this->sprintId = $sprintId;
    }

    public function remove(): void
    {
        $sprint = \App\Domain\Sprint\Models\Sprint::query()->findOrFail($this->sprintId);
        RemoveSprintAction::execute($this->sprintId);
        Flux::toast(text: 'Sprint deletada com sucesso!', heading: 'Sucesso', variant: 'success');
        $this->redirect(route('sprints.index', [$sprint->project->project_code]), navigate: true);
    }

    public function render()
    {
        return view('livewire.sprints.sprint-delete');
    }
}
