<?php

namespace App\Filament\Resources\GradingSheets;

use App\Filament\Resources\GradingSheets\Pages\EditGradingSheet;
use App\Filament\Resources\GradingSheets\Pages\ListGradingSheets;
use App\Filament\Resources\GradingSheets\Pages\ViewGradingSheet;
use App\Filament\Resources\GradingSheets\Pages\ViewGradingSheetDetails;
use App\Filament\Resources\GradingSheets\Schemas\GradingSheetsForm;
use App\Filament\Resources\GradingSheets\Tables\GradingSheetsTable;
use App\Models\Load;
use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GradingSheetsResource extends Resource
{
    protected static ?string $model = Load::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;


    protected static ?string $navigationLabel = 'My Grading Sheets';

    protected static ?int $navigationSort = 10;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    public static function form(Schema $schema): Schema
    {
        return GradingSheetsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GradingSheetsTable::configure($table);
    }

    public static function getModelLabel(): string
    {
        return 'Grading Sheet';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Grading Sheets';
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $record = method_exists($page, 'getRecord') ? $page->getRecord() : null;
        $showUpload = $record?->grading_sheet_status === 'pending';
        $items = [
            ViewGradingSheet::class,
            ViewGradingSheetDetails::class,
        ];

        if ($showUpload) {
            $items[] = EditGradingSheet::class;
        }

        return $page->generateNavigationItems($items);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGradingSheets::route('/'),
            'view' => ViewGradingSheet::route('/{record}'),
            'details' => ViewGradingSheetDetails::route('/{record}/details'),
            'edit' => EditGradingSheet::route('/{record}/edit'),
        ];
    }
}
