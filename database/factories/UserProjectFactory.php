<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\{Project, User, UserProject};
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProjectFactory extends Factory
{
    protected $model = UserProject::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
        ];
    }
}
