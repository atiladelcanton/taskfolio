<?php

namespace App\Domain\Sprint\Actions;

use App\Domain\Sprint\DTOs\SprintData;
use App\Domain\Sprint\Models\Sprint;
use Illuminate\Support\Str;

final class UpdateSprintAction
{
    public static function execute(int $sprintId, SprintData $data): bool
    {
        $sprintCode = sprintf("sprint-%s-%d", Str::slug($data->name), $sprintId);
        return Sprint::query()
            ->findOrFail($sprintId)
            ->update([
                'sprint_code' => $sprintCode,
                'name' => $data->name,
                'date_start' => $data->startDate->format('Y-m-d'),
                'date_end' => $data->endDate->format('Y-m-d'),
                'status' => $data->status->value,
            ]);
    }
}
