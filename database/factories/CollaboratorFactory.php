<?php

namespace Database\Factories;

use App\Models\Collaborator;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CollaboratorFactory extends Factory
{
    protected $model = Collaborator::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'hourly_rate' => $this->faker->randomFloat(),
            'pix' => $this->faker->word(),
            'bank_name' => $this->faker->name(),

            'user_id' => User::factory(),
        ];
    }
}
