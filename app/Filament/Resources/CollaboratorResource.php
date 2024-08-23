<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollaboratorResource\Pages;
use App\Models\Collaborator;
use App\Models\CollaboratorProject;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CollaboratorResource extends Resource
{
    protected static ?string $model = Collaborator::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Colaborador';

    protected static ?string $pluralModelLabel = 'Colaboradores';

    protected static ?string $navigationLabel = 'Colaborador';

    protected static ?string $pluralLabel = 'Colaboradores';

    protected static ?string $navigationGroup = 'Administrativo';

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user_id'),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('hourly_rate'),

                TextColumn::make('pix')->copyable(),
                TextColumn::make('projects.name')
                    ->badge()
                    ->color('success')
                    ->label('Projetos'),

                TextColumn::make('bank_name'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()->successNotification(
                    Notification::make()
                        ->success()
                        ->title('User deleted')
                        ->body('The user has been deleted successfully.'),
                ),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->successNotification(
                        Notification::make()
                            ->success()
                            ->title('User deleted')
                            ->body('The user has been deleted successfully.'),
                    ),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCollaborators::route('/'),
            'create' => Pages\CreateCollaborator::route('/create'),
            'edit' => Pages\EditCollaborator::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }

}
