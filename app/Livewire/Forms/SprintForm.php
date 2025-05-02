<?php

namespace App\Livewire\Forms;

use App\Domain\Sprint\Actions\CreateSprintAction;
use App\Domain\Sprint\Actions\UpdateSprintAction;
use App\Domain\Sprint\DTOs\SprintData;
use App\Domain\Sprint\Enums\SprintStatus;
use App\Domain\Sprint\Models\Sprint;
use Flux\DateRange;
use Illuminate\Validation\ValidationException;
use Livewire\Form;

class SprintForm extends Form
{
    public ?string $name;
    public ?DateRange $range;

    public ?string $status;

    public function setSprint(Sprint $sprint): void
    {
        $this->name = $sprint->name;
        $this->range = new DateRange($sprint->date_start, $sprint->date_end);
        $this->status = $sprint->status->value;
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|min:3',
        ];
    }

    /**
     * @throws ValidationException
     */
    public function store(int $projectId): void
    {
        $this->validate();

        $data = new SprintData(
            $projectId,
            $this->name,
            $this->range->getStartDate(),
            $this->range->getEndDate(),
            SprintStatus::Pending
        );

        CreateSprintAction::execute($data);
    }

    public function update(int $projectId, int $sprintId): void
    {
        $this->validate();

        $data = new SprintData(
            $projectId,
            $this->name,
            $this->range->getStartDate(),
            $this->range->getEndDate(),
            SprintStatus::from($this->status)
        );

        UpdateSprintAction::execute($sprintId, $data);
    }
}
