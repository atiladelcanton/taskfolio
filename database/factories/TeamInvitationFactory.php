<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Invitation\Models\TeamInvitation;
use App\Domain\Team\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TeamInvitationFactory extends Factory
{
    protected $model = TeamInvitation::class;

    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'billing_type' => $this->faker->word(),
            'billing_rate' => $this->faker->randomNumber(),
            'status' => $this->faker->randomNumber(),
            'invitation_code' => $this->faker->word(),
            'expires_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'team_id' => Team::factory(),
        ];
    }
}
