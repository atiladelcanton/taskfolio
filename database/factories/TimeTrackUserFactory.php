<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\{Task, TimeTrackUser, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class TimeTrackUserFactory extends Factory
{
    protected $model = TimeTrackUser::class;

    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'date' => fake()->date(),
            'total_hours' => fake()->randomFloat(2, 0.5, 8),
        ];
    }
}
