<?php

namespace App\Domain\Sprint\Actions;

use App\Domain\Sprint\DTOs\SprintData;
use App\Domain\Sprint\Models\Sprint;
use Illuminate\Support\Str;

final class CreateSprintAction
{
    public static function execute(SprintData $data): Sprint
    {
        $maxId = Sprint::query()->max('id');
        $code = $maxId > 0 ? $maxId + 1 : 1;
        $sprintCode = sprintf("sprint-%s-%d", Str::slug($data->name), $code);
        return Sprint::query()->create([
            'sprint_code' => $sprintCode,
            'project_id' => $data->projectId,
            'name' => $data->name,
            'date_start' => $data->startDate->format('Y-m-d'),
            'date_end' => $data->endDate->format('Y-m-d'),
            'status' => $data->status->value,
        ]);
    }
}
