<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\{Project, User, UserProject};
use Illuminate\Database\Seeder;
use Random\RandomException;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws RandomException
     */
    public function run(): void
    {
        $users = User::all();

        Project::factory(5)
            ->create([
                'owner_id' => $users->random()->id,
            ])
            ->each(function ($project) use ($users) {
                $projectUsers = $users->random(random_int(3, 5));

                foreach ($projectUsers as $user) {
                    UserProject::create([
                        'project_id' => $project->id,
                        'user_id' => $user->id,
                    ]);
                }
            });
    }
}
