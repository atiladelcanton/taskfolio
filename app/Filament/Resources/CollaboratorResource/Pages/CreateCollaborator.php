<?php

namespace App\Filament\Resources\CollaboratorResource\Pages;

use App\Filament\Resources\CollaboratorResource;
use App\Filament\Services\UserService;
use App\Models\Project;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use JetBrains\PhpStorm\NoReturn;
use Leandrocfe\FilamentPtbrFormFields\Money;

class CreateCollaborator extends CreateRecord
{
    protected static string $resource = CollaboratorResource::class;

    private array $selectedProjects = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(3)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome Completo')
                            ->required(),
                        TextInput::make('email')
                            ->label('E-mail')
                            ->live()
                            ->unique()
                            ->email()
                            ->required(),

                        Select::make('projects')
                            ->options(function () {
                                return Project::query()
                                    ->with('client')
                                    ->get()
                                    ->mapWithKeys(function ($project) {
                                        return [$project->id => "{$project->client->name}: {$project->name}"];
                                    });
                            })
                            ->label('Projetos')
                            ->multiple()
                            ->preload()
                            ->required(),
                        Money::make('hourly_rate')
                            ->label('Preço por Hora')
                            ->required(),
                        TextInput::make('pix')->label('Chave Pix'),

                        TextInput::make('bank_name')->label('Agencia Bancária'),
                    ]),
            ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $projects = $data['projects'] ?? [];
        unset($data['projects']);

        $userService = new UserService();
        $user = $userService->createUserCollaborato($data);
        $data['user_id'] = $user->id;
        $this->selectedProjects = $projects;

        return $data;
    }

    #[NoReturn]
    protected function afterCreate(): void
    {
        if (! empty($this->selectedProjects)) {
            foreach ($this->selectedProjects as $projectId) {
                \App\Models\CollaboratorProject::create([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'collaborator_id' => $this->record->id,
                    'project_id' => $projectId,
                ]);
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Colaborador cadastrado com sucesso!')
            ->body('O colaborador deve ser confirmado para acesso ao sistema');
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
