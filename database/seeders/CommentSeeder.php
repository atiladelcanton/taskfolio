<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Random\RandomException;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws RandomException
     */
    public function run(): void
    {
        $tasks = Task::all();
        $users = User::all();

        foreach ($tasks as $task) {
            // Add 0-5 comments to each task
            $commentCount = random_int(0, 5);

            for ($i = 0; $i < $commentCount; $i++) {
                Comment::create([
                    'task_id' => $task->id,
                    'user_id' => $users->random()->id,
                    'description' => fake()->paragraph(random_int(1, 3)),
                ]);
            }
        }
    }
}
