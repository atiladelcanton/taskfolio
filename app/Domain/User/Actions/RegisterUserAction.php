<?php

declare(strict_types=1);

namespace App\Domain\User\Actions;

use App\Domain\Project\Actions\CreateDefaultProject;
use App\Domain\User\DTOs\UserData;
use App\Models\User;

class RegisterUserAction
{
    public function __construct(protected CreateUserAction $createUserAction)
    {
    }

    /**
     * Register a new user and send a verification email
     */
    public function execute(UserData $userData): User
    {
        $user = $this->createUserAction->execute($userData);

        $createDefaultProject = new CreateDefaultProject;
        $createDefaultProject->execute($user);

        return $user;
    }
}
