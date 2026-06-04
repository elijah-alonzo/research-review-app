<?php

namespace App\Filament\Resources\GradingSheetApprovals;

use App\Filament\Resources\GradingSheetApprovals\Pages\ListGradingSheetApprovals;
use App\Filament\Resources\GradingSheetApprovals\Pages\ViewGradingSheetApproval;
use App\Filament\Resources\GradingSheetApprovals\Pages\ViewGradingSheetApprovalDetails;
use App\Filament\Resources\GradingSheetApprovals\Tables\GradingSheetApprovalsTable;
use App\Models\Load;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GradingSheetApprovalsResource extends Resource
{
    protected static ?string $model = Load::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static UnitEnum|string|null $navigationGroup = 'Grading Sheet Management';

    protected static ?string $navigationLabel = 'Grading Sheet Submissions';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user?->hasRole('Registrar') && $user->program_id) {
            return $query->where('program_id', $user->program_id);
        }

        return $query;
    }

    public static function table(Table $table): Table
    {
        return GradingSheetApprovalsTable::configure($table);
    }

    public static function getModelLabel(): string
    {
        return 'Grading Sheet Submission';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Grading Sheet Submissions';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('ViewAny:GradingSheetApproval') ?? false;
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()?->can('View:GradingSheetApproval') ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGradingSheetApprovals::route('/'),
            'view' => ViewGradingSheetApproval::route('/{record}'),
            'details' => ViewGradingSheetApprovalDetails::route('/{record}/details'),
        ];
    }
}
