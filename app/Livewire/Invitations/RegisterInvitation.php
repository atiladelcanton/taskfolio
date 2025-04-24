<?php

declare(strict_types=1);

namespace App\Livewire\Invitations;

use App\Domain\Invitation\Actions\ValidateInvitationCodeAction;
use App\Domain\Invitation\Models\TeamInvitation;
use App\Domain\Team\Models\Team;
use App\Domain\User\Actions\RegisterUserAction;
use App\Domain\User\DTOs\UserData;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class RegisterInvitation extends Component
{
    public string $name = '';

    public string $email = '';

    public string $emailConfirmation = '';

    public string $registrationCode = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(): void
    {
        if (! app(ValidateInvitationCodeAction::class)->validate()) {
            $this->redirect(route('home'), navigate: true);

            return;
        }

        $this->registrationCode = request()->get('token', '');
        $this->emailConfirmation = request()->get('email', '');
        $this->email = $this->emailConfirmation;
    }

    public function render()
    {
        return view('livewire.invitations.register-invitation');
    }

    public function register(): void
    {
        try {
            DB::beginTransaction();

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

            $user = app(RegisterUserAction::class)->execute($userData);

            $teamInvitation = TeamInvitation::query()
                ->where('email', $this->emailConfirmation)
                ->where('invitation_code', $this->registrationCode)
                ->firstOrFail();

            Team::create([
                'owner_id' => $teamInvitation->team->owner->id,
                'user_id' => $user->id,
                'billing_type' => $teamInvitation->billing_type,
                'billing_rate' => $teamInvitation->billing_rate,
                'cost_rate' => $teamInvitation->cost_rate,
                'role' => $teamInvitation->role,
            ]);

            $teamInvitation->delete();

            DB::commit();
            $this->redirect(route('login'), navigate: true);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->addError('status', 'Ocorreu um erro ao processar seu registro: '.$e->getMessage());
        }
    }
}
