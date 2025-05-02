<?php

declare(strict_types=1);

namespace App\Livewire\Teams;

use App\Domain\Invitation\Models\TeamInvitation;
use App\Livewire\Forms\CollaboratorForm;
use App\Domain\Team\Actions\{GetTeamsAndSearchableAction, ValidateIfEmailExistisAtTeam};
use App\Domain\Team\RoleTeamEnum;

use Flux\Flux;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Application;
use Livewire\Attributes\{Layout, Title};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.app')]
#[Title('Gerenciamento de Time')]
class Team extends Component
{
    use WithPagination;

    public string $searchTerm = '';

    public CollaboratorForm $collaboratorForm;

    public ?int $collaboratorId = 0;
    public ?int $invitationId = 0;
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

    public function addCollaborator(): void
    {
        $emailExists = ValidateIfEmailExistisAtTeam::execute($this->collaboratorForm->email);

        if ($emailExists) {
            Flux::toast(
                text: 'Este e-mail já está cadastrado no seu time',
                heading: 'Aviso',
                variant: 'warning'
            );

            return;
        }
        if($this->collaboratorForm->billing_rate > $this->collaboratorForm->cost_rate){
            Flux::toast(
                text: 'O valor de repasse está superior ao montante cobrado.',
                heading: 'Aviso',
                variant: 'warning'
            );
            return;
        }
        $this->collaboratorForm->store();
        Flux::toast(
            text: 'Convite enviado com sucesso, agora é só aguardar!',
            heading: 'Convite Enviado',
            variant: 'success'
        );
        $this->redirect(route('team'), navigate: true);
    }



    public function editInvite(string $collaboratorId): void
    {
        $this->collaboratorId = (int)$collaboratorId;
        $team = \App\Domain\Team\Models\Team::query()->with(['user'])->where('user_id',$this->collaboratorId)->first();
        if (!$team) {
            $this->collaboratorId = null;
        }
        $team->email = $team->user->email;
        $this->collaboratorForm->fill($team);

        $this->modal('new-colaborator')->show();
    }

    public function updateCollaborator():void{
        $team = \App\Domain\Team\Models\Team::query()->with(['user'])->where('user_id',$this->collaboratorId)->first();
        $this->collaboratorForm->update($team);
        $this->collaboratorForm->reset();
        $this->modal('new-colaborator')->close();
        Flux::toast(text: 'Colaborador atualizado com sucesso!', heading: 'Sucesso', variant: 'success');
    }

    public function confirmDeleteCollaborator(int $collaboratorId): void
    {
        $this->collaboratorId = $collaboratorId;
        $team = \App\Domain\Team\Models\Team::query()->whereUserId($this->collaboratorId);

        if (! $team) {
            $this->collaboratorId = null;
            Flux::toast(
                text: 'Colaborador inválido',
                heading: 'Aviso',
                variant: 'warning'
            );

            return;
        }
        $this->modal('delete-collaborator')->show();
    }
    public function deleteCollaborator(): void
    {
        $team = \App\Domain\Team\Models\Team::query()->find($this->collaboratorId);
        $team?->delete();
        Flux::toast(
            text: 'Colaborador removido com sucesso!',
            heading: 'Excluir Colaborador',
            variant: 'success'
        );
        $this->redirect(route('team'), navigate: true);
    }

    public function confirmCancelInvite(string $email):void{

        $invite = TeamInvitation::query()->where('email',$email)->first();
        if(!$invite){
            $this->invitationId = null;
            Flux::toast(
                text: 'Convite inválido',
                heading: 'Aviso',
                variant: 'warning'
            );

            return;
        }
        $this->invitationId = $invite->id;
        $this->modal('delete-invite')->show();
    }

    public function cancelInvite():void{
        $invite = TeamInvitation::query()->find($this->invitationId);
        $invite?->forceDelete();
        Flux::toast(
            text: 'Convite cancelado com sucesso!',
            heading: 'Cancelar Convite',
            variant: 'success'
        );
        $this->redirect(route('team'), navigate: true);
    }

}
