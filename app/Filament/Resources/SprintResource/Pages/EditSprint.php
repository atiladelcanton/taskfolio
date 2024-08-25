<?php

namespace App\Filament\Resources\SprintResource\Pages;

use App\Filament\Resources\SprintResource;
use App\Models\Project;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditSprint extends EditRecord
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

    protected function mutateFormDataBeforeSave(array $data): array
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
                            ->minDate(fn (callable $get) => $get('start_date'))
                            ->required(),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
