<?php

namespace App\Filament\Widgets;

use App\Models\Program;
use App\Models\Subject;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->can('ViewDashboardStats') ?? false;
    }

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getCards(): array
    {
        return [
            Stat::make('Users', User::count())
                ->description('Total registered users')
                ->color('primary')
                ->chart([1, 4, 2, 4, 5, 6, 7])
                ->descriptionIcon('heroicon-o-user-group'),
            Stat::make('Programs', Program::count())
                ->description('Active programs')
                ->color('primary')
                ->chart([1, 4, 2, 4, 5, 6, 7])
                ->descriptionIcon('heroicon-o-academic-cap'),
            Stat::make('Courses', Subject::count())
                ->description('Offered courses')
                ->color('primary')
                ->chart([1, 4, 2, 4, 5, 6, 7])
                ->descriptionIcon('heroicon-o-book-open'),
        ];
    }
}
