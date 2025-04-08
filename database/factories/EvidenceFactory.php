<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\{Evidence, Task};
use Illuminate\Database\Eloquent\Factories\Factory;

class EvidenceFactory extends Factory
{
    protected $model = Evidence::class;

    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'name' => fake()->words(3, true),
            'url' => fake()->url(),
        ];
    }
}
