<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\{Board, Project};
use Illuminate\Database\Seeder;

class BoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            $boardNames = ['Backlog', 'To Do', 'In Progress', 'Review', 'Done'];
            foreach ($boardNames as $boardName) {
                Board::create([
                    'project_id' => $project->id,
                    'name' => $boardName,
                ]);
            }
        }
    }
}
