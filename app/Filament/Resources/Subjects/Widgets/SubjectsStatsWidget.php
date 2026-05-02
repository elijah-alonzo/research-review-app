<?php

namespace App\Filament\Resources\Subjects\Widgets;

use App\Models\Subject;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SubjectsStatsWidget extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getCards(): array
    {
        return [
            Stat::make('Total Courses', Subject::count())
                ->description('All courses in the system')
                ->color('info')
                ->chart([1, 4, 2, 4, 5, 6, 7])
                ->descriptionIcon('heroicon-o-book-open'),
            Stat::make('Active Courses', Subject::where('is_active', true)->count())
                ->description('Courses currently active')
                ->color('success')
                ->chart([1, 4, 2, 4, 5, 6, 7])
                ->descriptionIcon('heroicon-o-check-badge'),
        ];
    }
}
