<?php

namespace Database\Factories;

use App\Models\Collaborator;
use App\Models\Task;
use App\Models\TimeLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TimeLogFactory extends Factory
{
    protected $model = TimeLog::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'action' => $this->faker->randomNumber(),
            'time_start' => Carbon::now(),
            'time_end' => Carbon::now(),

            'task_id' => Task::factory(),
            'collaborator_id' => Collaborator::factory(),
        ];
    }
}
