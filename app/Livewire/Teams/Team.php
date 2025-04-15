<?php

namespace App\Livewire\Teams;

use App\Domain\Team\Actions\GetTeamsAndSearchableAction;
use App\Domain\Team\Actions\ValidateIfEmailExistisAtTeam;
use App\Livewire\Forms\ColaboratorForm;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Flux\Flux;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Application;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Gerenciamento de Timer')]
class Team extends Component
{
    use WithPagination;
    public string $searchTerm = '';
    public ColaboratorForm $colaboratorForm;
    public function render(): View|Application|Factory|\Illuminate\View\View
    {
        // No Livewire, você pode obter a página atual com $this->page
        $members = GetTeamsAndSearchableAction::execute(
            page: $this->getPage(), // Função do Livewire WithPagination
            searchTerm: $this->searchTerm
        );

        return view('livewire.teams.team', [
            'members' => $members
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

        /**
         * @todo Adicionar logica para enviar convite para o colaborador
         */
        $this->colaboratorForm->store();
        Flux::toast(
            text: 'Convite enviado com sucesso, agora e so aguardar!',
            heading: 'Convite Enviado',
            variant: 'success'
        );
         $this->redirect(route('team'), navigate: true);
    }
}
