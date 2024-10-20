<?php

namespace App\Filament\Resources\SprintResource\Pages;

use App\Filament\Resources\SprintResource;
use Filament\Actions\CreateAction;
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
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ListSprints extends ListRecords
{
    protected static string $resource = SprintResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.client.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('project.name')
                    ->label('Projeto')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Sprint')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Data de Início')
                    ->date('d/m/Y'),
                TextColumn::make('end_date')
                    ->label('Data de Término')
                    ->date('d/m/Y'),
                ToggleColumn::make('default_sprint')
                    ->label('Sprint Default')

                    ->sortable(),
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
            CreateAction::make()->label('Novo Sprint')->icon('heroicon-s-plus'),
        ];
    }
}
