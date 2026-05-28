<?php

namespace App\Filament\Widgets;

use App\Models\Load;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MyLoadStatsWidget extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->can('View:MyLoadStatsWidget') ?? false;
    }

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getCards(): array
    {
        $baseQuery = Load::query()->where('user_id', auth()->id());
        $total = (clone $baseQuery)->count();
        $submitted = (clone $baseQuery)->where('grading_sheet_status', 'submitted')->count();
        $pending = (clone $baseQuery)->where('grading_sheet_status', 'pending')->count();
        $dueSoon = (clone $baseQuery)
            ->where('grading_sheet_status', 'pending')
            ->whereBetween('submission_deadline', [now(), now()->addDays(14)])
            ->count();

        return [
            Stat::make('My Loads', $total),
            Stat::make('Submitted', $submitted),
            Stat::make('Pending', $pending),
            Stat::make('Due Soon', $dueSoon),
        ];
    }
}
