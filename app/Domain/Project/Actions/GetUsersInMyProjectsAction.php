<?php

declare(strict_types=1);

namespace App\Domain\Project\Actions;

use App\Models\User;

class GetUsersInMyProjectsAction
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    public static function execute(): \Illuminate\Support\Collection
    {
        return User::whereHas('projects', static function ($query): void {
            $query->where('owner_id', auth()->id());
        })->select('id as user_id', 'name', 'avatar')->distinct()->get();
    }
}
