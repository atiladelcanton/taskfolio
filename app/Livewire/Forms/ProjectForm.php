<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Domain\Project\Models\Project;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProjectForm extends Form
{
    #[Validate('required|min:5')]
    public string $name = '';

    public string $description = '';

    public function store(): Project
    {
        $this->validate();

        return Project::query()->create([
            'name' => $this->name,
            'description' => $this->description,
            'owner_id' => auth()->user()->id,
            'project_code' => 'PJR-',
        ]);
    }

    public function update(Project $project): void
    {
        $project->update(['name' => $this->name,
            'description' => $this->description, ]);
    }
}
