<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use App\Models\TasksSprint;
use App\Support\Helper;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make('')
                        ->schema([
                            TextInput::make('name')
                                ->label('Titulo da Tarefa')
                                ->required()
                                ->columnSpanFull(),
                            MarkdownEditor::make('description')
                                ->toolbarButtons([
                                    'attachFiles',
                                    'blockquote',
                                    'bold',
                                    'bulletList',
                                    'codeBlock',
                                    'heading',
                                    'italic',
                                    'link',
                                    'orderedList',
                                    'redo',
                                    'strike',
                                    'table',
                                    'undo',
                                ])
                                ->required()
                                ->fileAttachmentsDirectory('tasks/evidencies')
                                ->columnSpanFull(),
                            FileUpload::make('evidences')
                                ->label('Evidencias')
                                ->multiple()
                                ->loadingIndicatorPosition('left')
                                ->panelLayout('integrated')
                                ->panelLayout('grid')
                                ->columnSpanFull()
                                ->directory('tasks'),
                        ])->grow(),
                    Section::make('')
                        ->schema([
                            Select::make('project_id')
                                ->options(function () {
                                    return Project::query()
                                        ->with('client')
                                        ->get()
                                        ->mapWithKeys(function ($project) {
                                            return [$project->id => "{$project->client->name}: {$project->name}"];
                                        });
                                })
                                ->live()
                                ->label('Projeto')
                                ->preload()
                                ->required(),
                            Select::make('colaborator_id')
                                ->label('Colaborador')
                                ->placeholder(function (Get $get) {

                                    return $get('project_id') ? 'Selecione o Colaborador' : 'Selecione o projeto primeiro';
                                })
                                ->disabled(function (Get $get) {

                                    return $get('project_id') ? false : true;
                                })
                                ->options(fn(Get $get
                                ) => $get('project_id') ? Project::with('collaborators')->find($get('project_id'))->collaborators()->get()->pluck('name',
                                    'id') : [])
                                ->searchable()
                                ->preload(),
                            Select::make('priority')
                                ->label('Prioridade')
                                ->required()
                                ->allowHtml()
                                ->searchable()
                                ->options([
                                    1 => '<span class="text-green-500">Baixa</span>',
                                    2 => '<span style="color: rgb(245 158 11);">Media</span>',
                                    3 => '<span class="text-danger-600">Alta</span>',
                                ]),
                            Select::make('type')
                                ->label('Tipo de Tarefa')
                                ->required()
                                ->allowHtml()
                                ->searchable()
                                ->options([
                                    1 => '<span class="text-danger-600">Bug</span>',
                                    2 => '<span class="text-green-500">Feature</span>',
                                ]),
                            Select::make('status')
                                ->label('Status')
                                ->required()
                                ->searchable()
                                ->options([
                                    1 => 'Backlog',
                                    2 => 'Em Andamento',
                                    3 => 'Validação',
                                    4 => 'Correção',
                                    5 => 'Concluído',
                                ])

                        ])->grow(false),
                ]),
            ])->columns(1);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $project = Project::find($data['project_id']);

        $data['task_code'] = Helper::generateTaskCode($project->client->name);
        $data['order'] = Task::query()->max('order') + 1;
        $data['total_hours'] = 0.0;

        return $data;
    }

    protected function afterCreate(): void
    {
        $task = Task::find($this->record->id);

        TasksSprint::create([
            'task_id' => $task->id,
            'sprint_id' => Sprint::query()->where('project_id', $task->project_id)->where('default_sprint',
                true)->first()->id,
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
