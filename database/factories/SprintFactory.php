<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\{Project, Sprint};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SprintFactory extends Factory
{
    protected $model = Sprint::class;

    public function definition(): array
    {
        return [
            'sprint_code' => $this->faker->word(),
            'name' => $this->faker->name(),
            'date_start' => Carbon::now(),
            'date_end' => Carbon::now(),
            'status' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'project_id' => Project::factory(),
        ];
    }
}
