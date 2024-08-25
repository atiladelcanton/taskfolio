<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Clusters\Tasks;
use App\Filament\Resources\TaskResource;
use App\Models\Task;
use Filament\Actions\CreateAction;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected static ?string $cluster = Tasks::class;

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
            ->columns([
                TextColumn::make('task_code')
                    ->searchable(),
                TextColumn::make('project.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('priority'),
                TextColumn::make('type'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Backlog' => 'gray',
                        'Em Andamento' => 'info',
                        'Validação' => 'warning',
                        'Concluído' => 'success',
                        'Correção' => 'danger',
                    }),
                TextColumn::make('total_hours'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
