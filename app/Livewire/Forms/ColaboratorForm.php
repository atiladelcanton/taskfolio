<?php

namespace App\Livewire\Forms;

use App\Domain\Invitation\Actions\CreateInvitationAction;
use App\Domain\Invitation\DTOs\TeamInvitationDTO;
use App\Domain\Team\Models\Team;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ColaboratorForm extends Form
{

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|numeric|min:0')]
    public int $billing_rate = 0;

    #[Validate('required|numeric|min:1|max:3')]
    public int $billing_type = 1;
    #[Validate('required|numeric|min:0')]
    public int $cost_rate = 0;
    public function store()
    {
        $this->validate();
        $teamInvitationDTO = new TeamInvitationDTO(
            email: $this->email,
            teamId: auth()->user()->team->id,
            billingType: $this->billing_type,
            billingRate: $this->billing_rate,
            costRate: $this->cost_rate,
        );

        CreateInvitationAction::execute($teamInvitationDTO);
        /**
         * @todo event de envio de convite
         */
    }

    public function update(): void
    {
        $this->validate();
    }

    public function setColaborator($colaborator): void
    {
        $this->email = $colaborator->email;
        $this->billiable_rate = $colaborator->billiable_rate;
        $this->billing_type = $colaborator->billing_type;
    }
}
