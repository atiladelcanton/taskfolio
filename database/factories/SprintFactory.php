<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Sprint;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SprintFactory extends Factory
{
    protected $model = Sprint::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->name(),
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now(),

            'project_id' => Project::factory(),
        ];
    }
}
