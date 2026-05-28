<?php

namespace App\Filament\Resources\GradingSheets\Pages;

use App\Filament\Resources\GradingSheets\GradingSheetsResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListGradingSheets extends ListRecords
{
    protected static string $resource = GradingSheetsResource::class;

    protected ?string $subheading = 'Browse, create, and manage your grading sheets.';

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();
        $userId = Auth::id();

        if (! $userId) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('user_id', $userId)
            ->with(['program', 'subject', 'academicYear']);
    }
}
