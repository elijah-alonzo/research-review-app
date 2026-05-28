<?php

namespace App\Filament\Widgets;

use App\Models\AcademicYear;
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
        $currentYear = AcademicYear::current();
        $activeTerm = Load::query()
            ->where('user_id', auth()->id())
            ->when($currentYear, fn ($query) => $query->where('academic_year_id', $currentYear->id))
            ->orderByDesc('submission_deadline')
            ->value('term');

        return [
            Stat::make('Academic Year', $currentYear?->year ?? 'N/A'),
            Stat::make('Active Semester', $activeTerm ?? 'N/A'),
        ];
    }
}
