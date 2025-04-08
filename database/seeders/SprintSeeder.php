<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\{Project, Sprint};
use Illuminate\Database\Seeder;

class SprintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            Sprint::factory(3)->create([
                'project_id' => $project->id,
            ]);
        }
    }
}
