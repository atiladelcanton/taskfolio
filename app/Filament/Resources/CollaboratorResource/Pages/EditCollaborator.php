<?php

namespace App\Filament\Resources\CollaboratorResource\Pages;

use App\Filament\Resources\CollaboratorResource;
use App\Models\Project;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Leandrocfe\FilamentPtbrFormFields\Money;

class EditCollaborator extends EditRecord
{
    protected static string $resource = CollaboratorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

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
                            ->unique(ignorable: $this->record)
                            ->email()
                            ->required(),
                        Select::make('projects')
                            ->relationship('projects', 'name')
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

}
