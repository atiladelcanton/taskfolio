<?php

declare(strict_types=1);

namespace App\Domain\Board\Listeners;

use App\Domain\Board\Models\Board;

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
    public function handle(\App\Domain\Board\Events\CreateDefaultBoard $event): void
    {
        $project = $event->project;
        $boards = ['Backlog', 'To Do', 'In Progress', 'Review', 'Done'];
        foreach ($boards as $board) {
            Board::create([
                'project_id' => $project->id,
                'name' => $board,
            ]);
        }
    }
}
