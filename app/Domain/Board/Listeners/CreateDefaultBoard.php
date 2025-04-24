<?php

declare(strict_types=1);

namespace App\Domain\Board\Listeners;

use App\Domain\Board\Models\Board;
use App\Domain\Project\Events\CreatedProjectEvent;

class CreateDefaultBoard
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
    public function handle(CreatedProjectEvent $event): void
    {
        $projectId = $event->projectId;
        $boards = ['Backlog', 'To Do', 'In Progress', 'Review', 'Done'];
        foreach ($boards as $board) {
            Board::create([
                'project_id' => $projectId,
                'name' => $board,
            ]);
        }
    }
}
