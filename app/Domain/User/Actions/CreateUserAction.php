<?php

declare(strict_types=1);

namespace App\Domain\User\Actions;

use App\Domain\User\DTOs\UserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

readonly class CreateUserAction
{
    public function execute(UserData $userData): User
    {
        $user = User::create([
            'name' => $userData->name,
            'email' => $userData->email,
            'password' => Hash::make($userData->password),
        ]);

        return $user;
    }
}
