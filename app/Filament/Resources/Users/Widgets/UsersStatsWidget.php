<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsersStatsWidget extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getCards(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->color('info')
                ->chart([1, 4, 2, 4, 5, 6, 7])
                ->descriptionIcon('heroicon-o-user-group'),
            Stat::make('Faculty', User::whereHas('roles', fn ($query) => $query->where('name', 'faculty'))->count())
                ->description('Standard faculty users')
                ->color('success')
                ->chart([1, 4, 2, 4, 5, 6, 7])
                ->descriptionIcon('heroicon-o-users'),
            Stat::make('Deans', User::whereHas('roles', fn ($query) => $query->where('name', 'dean'))->count())
                ->description('Admin users')
                ->color('warning')
                ->chart([1, 4, 2, 4, 5, 6, 7])
                ->descriptionIcon('heroicon-o-user'),

        ];
    }
}
