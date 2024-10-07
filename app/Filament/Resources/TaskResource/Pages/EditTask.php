<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Models\Project;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

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
                        Select::make('task_id')
                            ->relationship('taskFather', 'name')

                            ->label('Task Parent')
                            ->searchable()
                            ->preload()
                            ->required(),
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
                    ]),

                FileUpload::make('evidences')
                    ->label('Evidencias')
                    ->multiple()
                    ->loadingIndicatorPosition('left')
                    ->panelLayout('integrated')
                    ->panelLayout('grid')
                    ->downloadable()
                    ->columnSpanFull()
                    ->directory('tasks'),
            ]);
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
