<?php

namespace App\Actions\Task;

use App\Models\Sprint;

class CreateDefaultTask
{
    public static function handle(int $projectId): void
    {
        Sprint::create([
            'project_id' => $projectId,
            'name' => 'Backlog',
            'start_date' => now(),
            'end_date' => now()->addYears(30),
            'default_sprint' => true,
        ]);
    }
}
