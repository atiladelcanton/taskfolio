<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SprintResource\Pages;
use App\Filament\Resources\SprintResource\RelationManagers\TasksRelationManager;
use App\Models\Sprint;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SprintResource extends Resource
{
    protected static ?string $model = Sprint::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function getRelations(): array
    {
        return [
            TasksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSprints::route('/'),
            'create' => Pages\CreateSprint::route('/create'),
            'view' => Pages\ViewSprint::route('/{record}'),
            'edit' => Pages\EditSprint::route('/{record}/edit'),
        ];
    }
}
