<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Load;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;

class MyAssignedGradingSheetsWidget extends Widget
{
    use InteractsWithPageFilters;

    protected string $view = 'app.widgets.my-assigned-grading-sheets-tracker'; // nigga

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->can('View:MyAssignedGradingSheetsWidget') ?? false;
    }

    public function getViewData(): array
    {
        $filters = $this->filters ?? [];

        $loads = Load::query()
            ->where('user_id', auth()->id())
            ->when(! empty($filters['academic_year_id']), fn (Builder $query) => $query->where('academic_year_id', $filters['academic_year_id']))
            ->when(! empty($filters['term']), fn (Builder $query) => $query->where('term', $filters['term']))
            ->with(['program', 'subject', 'academicYear'])
            ->orderByDesc('academic_year_id')
            ->orderBy('term')
            ->get();

        return [
            'loads' => $loads,
        ];
    }
}
