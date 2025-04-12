<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Project\Models\Project;
use App\Models\{Sprint};
use Illuminate\Database\Eloquent\Factories\Factory;

class SprintFactory extends Factory
{
    protected $model = Sprint::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+30 days');
        $endDate = fake()->dateTimeBetween($startDate, '+30 days');

        return [
            'sprint_code' => 'SPR-'.fake()->unique()->numerify('####'),
            'project_id' => Project::factory(),
            'name' => 'Sprint '.fake()->unique()->numerify('##'),
            'date_start' => $startDate,
            'date_end' => $endDate,
            'status' => fake()->randomElement(['ongoing', 'completed', 'pending']),
        ];
    }
}
