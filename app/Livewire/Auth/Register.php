<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Domain\User\Actions\RegisterUserAction;
use App\Domain\User\DTOs\UserData;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    protected $registerUserAction;

    public function boot(RegisterUserAction $registerUserAction)
    {
        $this->registerUserAction = $registerUserAction;
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $userData = new UserData(
            name: $validated['name'],
            email: $validated['email'],
            password: $validated['password'],
        );
        $this->registerUserAction->execute($userData);

        $this->redirect(route('verification.notice', absolute: false), navigate: true);
    }
}
