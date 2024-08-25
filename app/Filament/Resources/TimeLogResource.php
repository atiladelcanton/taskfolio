<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeLogResource\Pages;
use App\Models\TimeLog;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TimeLogResource extends Resource
{
    protected static ?string $model = TimeLog::class;

    protected static ?string $slug = 'time-logs';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn (?TimeLog $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn (?TimeLog $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                Select::make('task_id')
                    ->relationship('task', 'name')
                    ->searchable()
                    ->required(),

                Select::make('collaborator_id')
                    ->relationship('collaborator', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('action')
                    ->required()
                    ->integer(),

                DatePicker::make('time_start'),

                DatePicker::make('time_end'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('task.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('collaborator.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('action'),

                TextColumn::make('time_start')
                    ->date(),

                TextColumn::make('time_end')
                    ->date(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimeLogs::route('/'),
            'create' => Pages\CreateTimeLog::route('/create'),
            'edit' => Pages\EditTimeLog::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['task', 'collaborator']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['task.name', 'collaborator.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->task) {
            $details['Task'] = $record->task->name;
        }

        if ($record->collaborator) {
            $details['Collaborator'] = $record->collaborator->name;
        }

        return $details;
    }
}
