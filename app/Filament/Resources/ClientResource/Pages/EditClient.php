<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Actions\Clients\UpdateUserClient;
use App\Filament\Resources\ClientResource;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;
use Rawilk\FilamentPasswordInput\Password;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Novo Cliente';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(3)
                    ->schema([

                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->autocomplete(false)
                            ->label('E-mail')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Password::make('password_user')
                            ->autocomplete(false)
                            ->label('Senha'),
                        PhoneNumber::make('phone')
                            ->label('Telefone'),
                        FileUpload::make('avatar_url')
                            ->uploadingMessage('Realizando upload...')
                            ->directory('clients')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper()
                    ])
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Deletar')->icon('heroicon-s-trash'),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {

        UpdateUserClient::make(data:$data,userId:$this->record->user_id);
        return $data;
    }



    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Cliente atualizado com sucesso!');
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
