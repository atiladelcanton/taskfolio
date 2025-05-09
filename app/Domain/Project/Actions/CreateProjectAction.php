<?php

declare(strict_types=1);

namespace App\Domain\Project\Actions;

use App\Domain\Project\DTOs\ProjectData;
use App\Domain\Project\Models\Project;
use Illuminate\Support\Str;

class CreateProjectAction
{
    /**
     * Cria um novo projeto usando Eloquent diretamente
     */
    public function execute(ProjectData $data): Project
    {
        $projectCode = $data->projectCode ??Str::slug($data->name,'-');

        $project = Project::create([
            'project_code' => $projectCode,
            'name' => $data->name,
            'description' => $data->description,
            'owner_id' => $data->ownerId,
        ]);
        $project->project_code = $projectCode.'-'.$project->id;
        $project->save();
        return $project;
    }
}
