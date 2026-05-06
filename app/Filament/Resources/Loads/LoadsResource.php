<?php

namespace App\Filament\Resources\Loads;

use App\Filament\Resources\Loads\Pages\CreateLoads;
use App\Filament\Resources\Loads\Pages\EditLoads;
use App\Filament\Resources\Loads\Pages\ListLoads;
use App\Filament\Resources\Loads\Schemas\LoadsForm;
use App\Filament\Resources\Loads\Tables\LoadsTable;
use App\Models\Load;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LoadsResource extends Resource
{
    protected static ?string $model = Load::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookmarkSquare;

    protected static UnitEnum|string|null $navigationGroup = 'Grading Sheet Management';

    protected static ?string $navigationLabel = 'Faculty\'s Grading Sheet';

    public static function form(Schema $schema): Schema
    {
        return LoadsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoadsTable::configure($table);
    }

    public static function getModelLabel(): string
    {
        return 'Faculty Load';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Faculty Loads';
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLoads::route('/'),
            'create' => CreateLoads::route('/create'),
            'edit' => EditLoads::route('/{record}/edit'),
        ];
    }
}
