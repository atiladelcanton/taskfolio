<?php

declare(strict_types=1);

namespace App\Livewire\Teams;

use App\Domain\Team\Actions\{GetTeamsAndSearchableAction, ValidateIfEmailExistisAtTeam};
use App\Domain\Team\RoleTeamEnum;
use App\Livewire\Forms\ColaboratorForm;
use Flux\Flux;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Application;
use Livewire\Attributes\{Layout, Title};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.app')]
#[Title('Gerenciamento de Timer')]
class Team extends Component
{
    use WithPagination;

    public string $searchTerm = '';

    public ColaboratorForm $colaboratorForm;

    public ?int $collaboratorId = null;

    public function render(): View|Application|Factory|\Illuminate\View\View
    {
        $members = GetTeamsAndSearchableAction::execute(
            page: $this->getPage(),
            searchTerm: $this->searchTerm
        );
        $rolesTeam = RoleTeamEnum::getRoles();

        return view('livewire.teams.team', [
            'members' => $members,
            'rolesTeam' => $rolesTeam,
        ]);
    }

    public function closeModal(): void
    {
        $this->reset();
    }

    public function addColaborator(): void
    {
        $emailExists = ValidateIfEmailExistisAtTeam::execute($this->colaboratorForm->email);

        if ($emailExists) {
            Flux::toast(
                text: 'Este email já está cadastrado no seu time',
                heading: 'Aviso',
                variant: 'warning'
            );

            return;
        }

        $this->colaboratorForm->store();
        Flux::toast(
            text: 'Convite enviado com sucesso, agora e so aguardar!',
            heading: 'Convite Enviado',
            variant: 'success'
        );
        $this->redirect(route('team'), navigate: true);
    }

    public function confirmDeleteCollaborator(int $collaboratorId): void
    {
        $this->collaboratorId = $collaboratorId;
        $team = \App\Domain\Team\Models\Team::query()->find($this->collaboratorId);
        if (! $team) {
            $this->collaboratorId = null;
            Flux::toast(
                text: 'Colaborador invalido',
                heading: 'Aviso',
                variant: 'warning'
            );

            return;
        }
        self::modal('delete-collaborator')->show();
    }

    public function deleteCollaborator(): void
    {
        $team = \App\Domain\Team\Models\Team::query()->find($this->collaboratorId);
        $team?->delete();
        Flux::toast(
            text: 'Convite enviado com sucesso, agora e so aguardar!',
            heading: 'Convite Enviado',
            variant: 'success'
        );
        $this->redirect(route('team'), navigate: true);
    }
}
