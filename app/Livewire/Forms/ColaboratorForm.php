<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Domain\Invitation\Actions\CreateInvitationAction;
use App\Domain\Invitation\DTOs\TeamInvitationDTO;
use App\Domain\Invitation\Events\{UserProjectInvitationEvent, UserRegistrationInvitationEvent};
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ColaboratorForm extends Form
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|numeric|min:0')]
    public int $billing_rate = 0;

    #[Validate('required|numeric|min:1|max:3')]
    public int $role = 0;

    #[Validate('required|numeric|min:1|max:3')]
    public int $billing_type = 0;

    #[Validate('required|numeric|min:0')]
    public int $cost_rate = 0;

    public function store(): void
    {
        $this->validate();
        $teamInvitationDTO = new TeamInvitationDTO(
            email: $this->email,
            teamId: auth()->user()->team->id,
            billingType: $this->billing_type,
            billingRate: $this->billing_rate,
            costRate: $this->cost_rate,
            role: $this->role,
        );

        $invite = CreateInvitationAction::execute($teamInvitationDTO);

        if (User::query()->where('email', $invite->email)->exists()) {
            UserProjectInvitationEvent::dispatch($invite);
        } else {
            UserRegistrationInvitationEvent::dispatch($invite);
        }
    }

    public function update(): void
    {
        $this->validate();
    }
}
