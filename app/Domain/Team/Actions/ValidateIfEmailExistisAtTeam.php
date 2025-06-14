<?php

declare(strict_types=1);

namespace App\Domain\Team\Actions;

use App\Domain\Team\Models\Team;

class ValidateIfEmailExistisAtTeam
{
    public static function execute(string $email): bool
    {
        return Team::query()
            ->whereHas('user', function ($query) use ($email): void {
                $query->where('email', $email);
            })
            ->where('owner_id', auth()->user()->id)
            ->exists();
    }
}
