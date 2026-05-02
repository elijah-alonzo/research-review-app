<?php

namespace App\Filament\Resources\Programs;

use App\Filament\Resources\Programs\Pages\CreatePrograms;
use App\Filament\Resources\Programs\Pages\EditPrograms;
use App\Filament\Resources\Programs\Pages\ListPrograms;
use App\Filament\Resources\Programs\Pages\ViewPrograms;
use App\Filament\Resources\Programs\RelationManagers\SubjectsRelationManager;
use App\Filament\Resources\Programs\Schemas\ProgramsForm;
use App\Filament\Resources\Programs\Tables\ProgramsTable;
use App\Models\Program;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class ProgramsResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static UnitEnum|string|null $navigationGroup = 'Academic Management';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    public static function form(Schema $schema): Schema
    {
        return ProgramsForm::configure($schema);
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();

        return $user?->isDean() ?? false;
    }

    public static function canAccess(): bool
    {
        return static::canViewAny();
    }

    public static function table(Table $table): Table
    {
        return ProgramsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SubjectsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPrograms::route('/'),
            'create' => CreatePrograms::route('/create'),
            'edit' => EditPrograms::route('/{record}/edit'),
            'view' => ViewPrograms::route('/{record}'),
        ];
    }
}
