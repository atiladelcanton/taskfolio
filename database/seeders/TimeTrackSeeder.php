<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\{Task, TimeTrack, TimeTrackUser, User};
use Illuminate\Database\Seeder;
use Random\RandomException;

class TimeTrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws RandomException
     */
    public function run(): void
    {
        $tasks = Task::all();
        $users = User::all();

        foreach ($tasks as $task) {
            // Get users from the project
            $projectUsers = User::whereHas('projects', function ($query) use ($task) {
                $query->whereHas('sprints', function ($query) use ($task) {
                    $query->where('id', $task->sprint_id);
                });
            })->get();

            if ($projectUsers->isEmpty()) {
                $projectUsers = $users->random(2);
            }

            // Create time tracking records
            foreach ($projectUsers->random(min(2, $projectUsers->count())) as $user) {
                // Individual time tracks (3-5 per task)
                for ($i = 0; $i < random_int(3, 5); $i++) {
                    $startTime = now()->subDays(random_int(1, 14))->subHours(random_int(1, 8));
                    $endTime = (clone $startTime)->addHours(random_int(1, 3))->addMinutes(random_int(0, 59));

                    TimeTrack::create([
                        'task_id' => $task->id,
                        'user_id' => $user->id,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                    ]);

                    // Calculate total hours worked for that date
                    $date = $startTime->format('Y-m-d');
                    $hoursWorked = $endTime->diffInMinutes($startTime) / 60;

                    // Add to TimeTrackUser (summary)
                    $timeTrackUser = TimeTrackUser::firstOrNew([
                        'task_id' => $task->id,
                        'user_id' => $user->id,
                        'date' => $date,
                    ]);

                    if ($timeTrackUser->exists) {
                        $timeTrackUser->total_hours += $hoursWorked;
                    } else {
                        $timeTrackUser->total_hours = $hoursWorked;
                    }

                    $timeTrackUser->save();
                }
            }
        }
    }
}
