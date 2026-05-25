<?php

namespace App\Filament\Widgets;

use App\Enums\AcademicYear;
use App\Models\Load;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AcademicContextWidget extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->can('View:AcademicContextWidget') ?? false;
    }

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getCards(): array
    {
        $currentYear = AcademicYear::current()->value;
        $activeTerm = Load::query()
            ->where('user_id', auth()->id())
            ->where('academic_year', $currentYear)
            ->orderByDesc('submission_deadline')
            ->value('term');

        return [
            Stat::make('Academic Year', $currentYear),
            Stat::make('Active Term', $activeTerm ?? 'N/A'),
        ];
    }
}
