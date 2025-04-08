<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\{Board, Project};
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
