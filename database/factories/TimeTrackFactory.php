<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\{Task, TimeTrack, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class TimeTrackFactory extends Factory
{
    protected $model = TimeTrack::class;

    public function definition(): array
    {
        $startTime = fake()->dateTimeBetween('-1 week', 'now');
        $endTime = fake()->dateTimeBetween($startTime, '+3 hours');

        return [
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'end_time' => null,
        ]);
    }
}
