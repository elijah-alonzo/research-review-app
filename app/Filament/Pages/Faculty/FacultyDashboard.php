<?php

namespace App\Filament\Pages\Faculty;

use App\Models\Load;
use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Support\Facades\Auth;

class FacultyDashboard extends BaseDashboard
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
        $userId = Auth::id();

        $pendingCount = Load::where('user_id', $userId)
            ->where('grading_sheet_status', 'pending')
            ->count();

        $toVerifyCount = Load::where('user_id', $userId)
            ->where('grading_sheet_status', 'to_verify')
            ->count();

        $toEndorseCount = Load::where('user_id', $userId)
            ->where('grading_sheet_status', 'to_endorse')
            ->count();

        $submittedCount = Load::where('user_id', $userId)
            ->where('grading_sheet_status', 'submitted')
            ->count();

        return [
            Stat::make('Pending', $pendingCount)
                ->description('Grading sheets awaiting upload')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->color('warning')
                ->icon('heroicon-o-document'),

            Stat::make('Ready to Verify', $toVerifyCount)
                ->description('Submitted for verification')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->color('info')
                ->icon('heroicon-o-clipboard-document-check'),

            Stat::make('Ready to Endorse', $toEndorseCount)
                ->description('Verified, awaiting endorsement')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->color('primary')
                ->icon('heroicon-o-check-badge'),

            Stat::make('Submitted', $submittedCount)
                ->description('Finalized grading sheets')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->color('success')
                ->icon('heroicon-o-check-circle'),
        ];
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('view_all_sheets')
                ->label('View All Grading Sheets')
                ->url(route('filament.faculty.resources.grading-sheets.index'))
                ->button(),
        ];
    }
}
