<?php

namespace App\Domain\Sprint\Actions;

use App\Domain\Sprint\DTOs\SprintData;
use App\Domain\Sprint\Models\Sprint;
use Illuminate\Support\Str;

final class RemoveSprintAction
{
    public static function execute(int $sprintId): bool
    {
        $sprint = Sprint::query()->findOrFail($sprintId);
        $sprint->tasks()->delete();
        return $sprint->delete();
    }
}
