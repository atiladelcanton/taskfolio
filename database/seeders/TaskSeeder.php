<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Board\Models\Board;
use App\Domain\Project\Models\Project;
use App\Domain\Sprint\Models\Sprint;
use App\Models\{Task, User};
use Illuminate\Database\Seeder;
use Random\RandomException;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws RandomException
     */
    public function run(): void
    {
        $sprints = Sprint::all();
        $users = User::all();

        foreach ($sprints as $sprint) {
            $project = Project::find($sprint->project_id);
            $boards = Board::where('project_id', $project->id)->get();

            // Create 5-10 tasks for each sprint
            $tasks = Task::factory(random_int(5, 10))->create([
                'sprint_id' => $sprint->id,
                'board_id' => $boards->random()->id,
                'responsible_id' => $users->random()->id,
            ]);

            // Add some subtasks
            foreach ($tasks->random(2) as $task) {
                Task::factory(random_int(2, 4))->create([
                    'sprint_id' => $sprint->id,
                    'board_id' => $task->board_id,
                    'responsible_id' => $users->random()->id,
                    'subtask_id' => $task->id,
                ]);
            }
        }
    }
}
