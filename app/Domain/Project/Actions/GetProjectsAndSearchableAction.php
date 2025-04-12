<?php

declare(strict_types=1);

namespace App\Domain\Project\Actions;

use App\Domain\Project\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;

class GetProjectsAndSearchableAction
{
    /**
     * Executes the action to get projects with optional search filtering.
     *
     * @param string|null $searchTerm Optional search term
     * @return LengthAwarePaginator<int, Project> The paginated projects
     */
    public static function execute(?string $searchTerm = null): LengthAwarePaginator
    {
        $userId = \Auth::id();

        return Project::query()
            ->when($searchTerm, fn ($q) => $q->where('name', 'like', '%'.$searchTerm.'%')
                ->orWhere('project_code', 'like', '%'.$searchTerm.'%')->orWhere('description', 'like', '%'.$searchTerm.'%'))
            ->where(function ($q) use ($userId): void {
                $q->where('owner_id', $userId)->orWhereHas('users', function ($q) use ($userId): void {
                    $q->where('users.id', $userId);
                });
            })->with(['owner', 'users', 'sprints'])->latest()->paginate(10);
    }
}
