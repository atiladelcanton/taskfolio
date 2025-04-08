<?php

declare(strict_types=1);

namespace App\Domain\User\Actions;

use App\Domain\User\DTOs\UserData;
use Illuminate\Auth\Events\Registered;

class RegisterUserAction
{
    protected $createUserAction;

    public function __construct(CreateUserAction $createUserAction)
    {
        $this->createUserAction = $createUserAction;
    }

    /**
     * Register a new user and send a verification email
     */
    public function execute(UserData $userData): User
    {
        $user = $this->createUserAction->execute($userData);

        event(new Registered($user));

        return $user;
    }
}
