<?php

namespace App\Livewire\Forms;

use App\Models\Project;
use LaravelIdea\Helper\App\Models\_IH_Project_C;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProjectForm extends Form
{
    #[Validate('required|min:5')]
    public $name = '';

    public $description = '';

    public function store(): Project
    {
        $this->validate();
        return Project::query()->create([
            'name' => $this->name,
            'description' => $this->description,
            'owner_id' => auth()->user()->id,
            'project_code' => 'PJR-'
        ]);
    }

    public function update(array|Project|_IH_Project_C|null $project): void
    {
        $project->update(['name' => $this->name,
            'description' => $this->description,]);
    }
}
