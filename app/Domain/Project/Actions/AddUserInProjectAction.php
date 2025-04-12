<?php

declare(strict_types=1);

namespace App\Domain\Project\Actions;

use App\Domain\Project\Models\Project;
use DomainException;
use Illuminate\Support\Facades\DB;

class AddUserInProjectAction
{
    /**
     * Add users to a specific project.
     *
     * @param  int  $projectId  The ID of the project
     * @param  array<int, mixed>  $participants  List of participants to add
     * @param  int  $userId  The ID of the user being added
     *
     * @throws DomainException When project is not found
     */
    public static function execute(int $projectId, array $participants, int $userId): void
    {
        $project = Project::query()->find($projectId);
        if (is_null($project)) {
            throw new DomainException('Project not found AddUserInProjectAction');
        }
        foreach ($participants as $participant) {
            $existingRecord = DB::table('user_projects')
                ->where('project_id', $projectId)
                ->where('user_id', $participant)
                ->first();
            if ($existingRecord) {
                DB::table('user_projects')
                    ->where('project_id', $projectId)
                    ->where('user_id', $participant)
                    ->update([
                        'deleted_at' => null,
                        'updated_at' => now(),
                    ]);
            } else {
                $project->users()->attach($participant);
            }
        }
    }
}
