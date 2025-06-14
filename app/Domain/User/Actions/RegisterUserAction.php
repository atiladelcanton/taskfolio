<?php

declare(strict_types=1);

namespace App\Domain\User\Actions;

use App\Domain\Project\Actions\CreateDefaultProjectAction;
use App\Domain\User\DTOs\UserData;
use App\Models\User;

class RegisterUserAction
{
    public function __construct(protected CreateUserAction $createUserAction) {}

    /**
     * Register a new user and send a verification email
     *
     * @throws \Exception
     */
    public function execute(UserData $userData): User
    {
        $user = $this->createUserAction->execute($userData);

        $createDefaultProject = new CreateDefaultProjectAction;
        $createDefaultProject->execute($user);

        return $user;
    }
}
