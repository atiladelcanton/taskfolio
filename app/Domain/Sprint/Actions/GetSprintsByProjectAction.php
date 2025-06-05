<?php

namespace App\Domain\Sprint\Actions;

use App\Domain\Sprint\Models\Sprint;
use Illuminate\Pagination\LengthAwarePaginator;

final class GetSprintsByProjectAction
{
    public static function execute(int $projectId): LengthAwarePaginator
    {
        return Sprint::query()
            ->where('project_id', $projectId)
            ->orderBy('id')
            ->paginate(10);
    }
}
