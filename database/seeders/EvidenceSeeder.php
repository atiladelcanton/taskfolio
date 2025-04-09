<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\{Evidence, Task};
use Illuminate\Database\Seeder;
use Random\RandomException;

class EvidenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws RandomException
     */
    public function run(): void
    {
        $tasks = Task::all();

        foreach ($tasks as $task) {
            // Add 0-3 evidence items to each task
            $evidenceCount = random_int(0, 3);

            for ($i = 0; $i < $evidenceCount; $i++) {
                $fileTypes = ['pdf', 'doc', 'jpg', 'png', 'xls'];
                $fileType = $fileTypes[array_rand($fileTypes)];
                $fileName = 'evidence_'.fake()->word().'.'.$fileType;

                Evidence::create([
                    'task_id' => $task->id,
                    'name' => $fileName,
                    'url' => fake()->url().'/'.$fileName,
                ]);
            }
        }
    }
}
