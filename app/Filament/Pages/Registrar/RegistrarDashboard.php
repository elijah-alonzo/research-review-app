<?php

namespace App\Filament\Pages\Registrar;

use App\Models\Load;
use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Support\Facades\Auth;

class RegistrarDashboard extends BaseDashboard
{
    public function getColumns(): int|array
    {
        return [
            'md' => 2,
            'xl' => 4,
        ];
    }

    protected function getStats(): array
    {
        $user = Auth::user();
        $programId = $user->program_id;

        // Query scoped to registrar's program
        $baseQuery = Load::when($programId, fn ($q) => $q->where('program_id', $programId));

        $toVerifyCount = (clone $baseQuery)
            ->where('grading_sheet_status', 'to_verify')
            ->count();

        $toEndorseCount = (clone $baseQuery)
            ->where('grading_sheet_status', 'to_endorse')
            ->count();

        $submittedCount = (clone $baseQuery)
            ->where('grading_sheet_status', 'submitted')
            ->count();

        $totalCount = $baseQuery->count();

        return [
            Stat::make('Awaiting Verification', $toVerifyCount)
                ->description('Sheets ready to verify')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->color('warning')
                ->icon('heroicon-o-clipboard-document-check'),

            Stat::make('Awaiting Endorsement', $toEndorseCount)
                ->description('Verified, needs endorsement')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->color('info')
                ->icon('heroicon-o-check-badge'),

            Stat::make('Submitted', $submittedCount)
                ->description('Finalized grading sheets')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Stat::make('Total Records', $totalCount)
                ->description('All grading sheets')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->color('primary')
                ->icon('heroicon-o-document-text'),
        ];
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('view_all_submissions')
                ->label('View All Submissions')
                ->url(route('filament.registrar.resources.grading-sheet-approvals.index'))
                ->button(),
        ];
    }
}
