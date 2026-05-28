<?php

namespace App\Filament\Resources\GradingSheetApprovals\Pages;

use App\Filament\Resources\GradingSheetApprovals\GradingSheetApprovalsResource;
use App\Models\Load;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListGradingSheetApprovals extends ListRecords
{
    protected static string $resource = GradingSheetApprovalsResource::class;

    protected ?string $subheading = 'Review and manage grading sheet submissions.';

    protected function getTableQuery(): Builder
    {
        return Load::query()
            ->with(['user', 'program', 'subject', 'academicYear'])
            ->orderByDesc('updated_at');
    }

}
