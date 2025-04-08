<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\{Board, Sprint, Task, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'task_code' => fake()->randomElement(['TASK-', 'BUG-', 'FEAT-']).fake()->unique()->numerify('####'),
            'sprint_id' => Sprint::factory(),
            'board_id' => Board::factory(),
            'type' => fake()->randomElement(['Task', 'BugFix', 'Feature', 'Other']),
            'responsible_id' => User::factory(),
            'subtask_id' => null,
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
    }

    public function subtask(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'subtask_id' => Task::factory(),
            ];
        });
    }

    public function taskType(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'Task',
            'task_code' => 'TASK-'.fake()->unique()->numerify('####'),
        ]);
    }

    public function bugType(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'BugFix',
            'task_code' => 'BUG-'.fake()->unique()->numerify('####'),
        ]);
    }
}
