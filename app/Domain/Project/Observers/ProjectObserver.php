<?php

declare(strict_types=1);

namespace App\Domain\Project\Observers;

use App\Domain\Project\Events\{CreatedProjectEvent, DeletedProject};
use App\Domain\Project\Models\Project;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        $project->project_code = 'PJR-'.$project->id;
        $project->save();

        event(new CreatedProjectEvent($project->id));
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        event(new DeletedProject($project->id));
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
