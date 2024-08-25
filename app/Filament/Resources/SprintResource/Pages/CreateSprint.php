<?php

namespace App\Filament\Resources\SprintResource\Pages;

use App\Filament\Resources\SprintResource;
use App\Models\Project;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateSprint extends CreateRecord
{
    protected static string $resource = SprintResource::class;

    public static function getNavigationLabel(): string
    {
        return 'Novo Sprint';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Novo Sprint';
    }

    public function getBreadcrumb(): string
    {
        return 'Novo Sprint';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $dateStart = Carbon::createFromFormat('d/m/Y', $data['start_date']);
        $dateEnd = Carbon::createFromFormat('d/m/Y', $data['end_date']);

        $data['start_date'] = $dateStart->format('Y-m-d');
        $data['end_date'] = $dateEnd->format('Y-m-d');

        return $data;

    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(4)
                    ->schema([
                        Select::make('project_id')
                            ->relationship('project', 'name')
                            ->options(function () {
                                return Project::query()
                                    ->with('client')
                                    ->get()
                                    ->mapWithKeys(function ($project) {
                                        return [$project->id => "{$project->client->name}: {$project->name}"];
                                    });
                            })
                            ->label('Projeto')
                            ->preload()
                            ->required(),
                        TextInput::make('name')
                            ->label('Nome do Sprint')
                            ->required(),
                        DatePicker::make('start_date')
                            ->label('Inicio do Sprint')->format('d/m/Y')
                            ->minDate(now())
                            ->reactive()
                            ->required(),
                        DatePicker::make('end_date')
                            ->label('Fim do Sprint')->format('d/m/Y')
                            ->minDate(fn (callable $get) => Carbon::parse($get('start_date'))->addDays(14))->reactive()
                            ->required(),
                    ]),
            ]);
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
            ->title('Sprint criado com sucesso!');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Sprint atualizado com sucesso!');
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
