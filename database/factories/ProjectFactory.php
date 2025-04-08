<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\{Project, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'project_code' => 'PRJ-'.fake()->unique()->numerify('####'),
            'owner_id' => User::factory(),
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
        ];
    }
}
