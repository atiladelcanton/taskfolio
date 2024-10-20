<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Leandrocfe\FilamentPtbrFormFields\Money;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(4)
                    ->schema([
                        Select::make('client_id')
                            ->label('Cliente')
                            ->relationship(name: 'client', titleAttribute: 'name')
                            ->preload()
                            ->searchable()
                            ->required(),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('hours_month')
                            ->numeric(),
                        Money::make('hourly_rate')
                            ->label('Preço por Hora')
                            ->required(),
                        Toggle::make('active')
                            ->label('Ativo')
                            ->inline(false)
                            ->default(true)
                            ->required(),
                        Textarea::make('description')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Projeto salvo com sucesso!');
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
