<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\{Project, User};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'project_code' => $this->faker->word(),
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'owner_id' => User::factory(),
        ];
    }
}
