<?php

namespace Database\Factories;

use App\Models\Collaborator;
use App\Models\Task;
use App\Models\TaskHistory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TaskHistoryFactory extends Factory
{
    protected $model = TaskHistory::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now(),

            'task_id' => Task::factory(),
            'collaborator_id' => Collaborator::factory(),
        ];
    }
}
