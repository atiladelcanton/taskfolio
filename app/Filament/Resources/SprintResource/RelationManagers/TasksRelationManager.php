<?php

namespace App\Filament\Resources\SprintResource\RelationManagers;

use App\Models\Project;
use App\Models\Task;
use App\Support\Helper;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Components\Tab;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    public function form(Form $form): Form
    {

        return $form

            ->schema([
                Split::make([
                    Section::make('')
                        ->schema([
                            Forms\Components\Hidden::make('sprint_id')
                                ->default($this->all()['ownerRecord']->id),
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
                            Select::make('collaborator_id')
                                ->label('Colaborador')
                                ->placeholder(function (Get $get) {

                                    return $get('project_id') ? 'Selecione o Colaborador' : 'Selecione o projeto primeiro';
                                })
                                ->disabled(function (Get $get) {

                                    return $get('project_id') ? false : true;
                                })
                                ->options(fn (Get $get
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
                                ]),

                        ])->grow(false),
                ]),
            ])

            ->columns(1);
    }

    public function getTabs(): array
    {
        return [
            '1' => Tab::make('Backlog')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereStatus(1))
                ->badge(Task::query()->whereStatus(1)->count()),
            '2' => Tab::make('Em Andamento')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereStatus(2))
                ->badge(Task::query()->whereStatus(2)->count()),
            '3' => Tab::make('Validação')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereStatus(3))
                ->badge(Task::query()->whereStatus(3)->count()),
            '4' => Tab::make('Correção')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereStatus(4))
                ->badge(Task::query()->whereStatus(4)->count()),
            '5' => Tab::make('Completo')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereStatus(5))
                ->badge(Task::query()->whereStatus(5)->count()),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')

            ->columns([
                TextColumn::make('task_code')
                    ->label('Código Task')
                    ->searchable(),
                TextColumn::make('collaborator.name')
                    ->label('Colaborador'),
                TextColumn::make('project.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'gray',
                        '2' => 'warning',
                        '3' => 'danger',
                    })
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            1 => 'Baixa',
                            2 => 'Média',
                            3 => 'Alta',
                            default => 'Desconhecida',
                        };
                    }),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '2' => 'gray',
                        '1' => 'warning',
                    })
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            1 => 'Bug',
                            2 => 'Feature',
                            default => 'Desconhecida',
                        };
                    }),
                TextColumn::make('total_hours'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->modalWidth(MaxWidth::FiveExtraLarge)
                    ->mutateFormDataUsing(function (array $data): array {
                        $project = Project::find($data['project_id']);

                        $data['task_code'] = Helper::generateTaskCode($project->client->name);
                        $data['order'] = Task::query()->max('order') + 1;
                        $data['total_hours'] = 0.0;

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
