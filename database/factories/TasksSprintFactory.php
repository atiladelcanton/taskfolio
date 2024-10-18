<?php

namespace Database\Factories;

use App\Models\Sprint;
use App\Models\Task;
use App\Models\TasksSprint;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TasksSprintFactory extends Factory
{
    protected $model = TasksSprint::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'sprint_id' => Sprint::factory(),
            'task_id' => Task::factory(),
        ];
    }
}
