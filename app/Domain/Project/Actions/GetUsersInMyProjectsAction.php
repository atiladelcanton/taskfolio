<?php

declare(strict_types=1);

namespace App\Domain\Project\Actions;

use App\Domain\Team\Models\Team;
use App\Models\User;

class GetUsersInMyProjectsAction
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    public static function execute(): \Illuminate\Support\Collection
    {
       return Team::query()
            ->with(['user' => function($query) {
                $query->select('id', 'name', 'avatar');
            }])
            ->where('owner_id', auth()->user()->id)
            ->select('id', 'user_id')
            ->get();


    }
}
