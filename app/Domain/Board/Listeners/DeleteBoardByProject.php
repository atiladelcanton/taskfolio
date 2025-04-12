<?php

namespace App\Domain\Board\Listeners;

use App\Domain\Board\Models\Board;
use App\Domain\Project\Events\DeletedProject;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DeleteBoardByProject
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DeletedProject $event): void
    {
        Board::query()->where('project_id', $event->projectId)->delete();
    }
}
