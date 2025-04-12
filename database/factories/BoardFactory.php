<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Board\Models\{Board};
use App\Domain\Project\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoardFactory extends Factory
{
    protected $model = Board::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'name' => fake()->randomElement(['Backlog', 'To Do', 'In Progress', 'Review', 'Done']),
        ];
    }
}
