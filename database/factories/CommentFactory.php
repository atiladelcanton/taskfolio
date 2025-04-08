<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\{Comment, Task, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'description' => fake()->paragraph(),
        ];
    }
}
