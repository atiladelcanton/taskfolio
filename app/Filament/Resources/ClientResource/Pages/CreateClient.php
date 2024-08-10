<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;
use Rawilk\FilamentPasswordInput\Password;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Novo Cliente';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password_user']),
        ]);
        $data['user_id'] = $user->id;

        return $data;
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
                            ->required()
                            ->label('Senha'),
                        PhoneNumber::make('phone')
                            ->label('Telefone'),
                        FileUpload::make('avatar_url')
                            ->uploadingMessage('Realizando upload...')
                            ->directory('clients')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper(),
                    ]),
            ]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Cliente cadastrado com sucesso!');
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
