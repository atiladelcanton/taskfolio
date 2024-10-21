<?php

namespace App\Filament\Resources\SprintResource\Pages;

use App\Filament\Resources\SprintResource;
use App\Filament\Resources\SprintResource\RelationManagers\TasksRelationManager;
use Filament\Resources\Pages\ViewRecord;

class ViewSprint extends ViewRecord
{
    protected static string $resource = SprintResource::class;

    public function getRelationManagers(): array
    {
        return [
            TasksRelationManager::class,
        ];
    }
}
