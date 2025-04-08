<?php

namespace Database\Factories;

use App\Models\Board;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BoardFactory extends Factory
{
    protected $model = Board::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'project_id' => Project::factory(),
        ];
    }
}
