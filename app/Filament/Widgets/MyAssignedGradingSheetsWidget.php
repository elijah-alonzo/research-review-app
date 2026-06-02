<?php

namespace App\Filament\Widgets;

use App\Models\Load;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class MyAssignedGradingSheetsWidget extends TableWidget
{
    use InteractsWithPageFilters;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->can('View:MyAssignedGradingSheetsWidget') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Assigned Grading Sheets')
            ->description('All grading sheets assigned to you based on the selected academic filters.')
            ->columns([
                TextColumn::make('program.name')
                    ->label('Program')
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-m-academic-cap')
                    ->searchable(),
                TextColumn::make('subject.name')
                    ->label('Subject')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-book-open')
                    ->searchable(),
                TextColumn::make('academicYear.year')
                    ->label('Academic Year')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('term')
                    ->label('Semester')
                    ->searchable(),
                TextColumn::make('grading_sheet_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => str($state)->replace('_', ' ')->title()->toString())
                    ->color(fn (string $state): string => match ($state) {
                        'submitted' => 'success',
                        'to_verify' => 'warning',
                        'to_endorse' => 'info',
                        default => 'gray',
                    }),
            ])
            ->query($this->getAssignedQuery())
            ->defaultPaginationPageOption(10);
    }

    protected function getAssignedQuery(): Builder
    {
        $filters = $this->filters ?? [];

        return Load::query()
            ->where('user_id', auth()->id())
            ->when(! empty($filters['academic_year_id']), fn (Builder $query) => $query->where('academic_year_id', $filters['academic_year_id']))
            ->when(! empty($filters['term']), fn (Builder $query) => $query->where('term', $filters['term']))
            ->with(['program', 'subject', 'academicYear'])
            ->orderByDesc('academic_year_id')
            ->orderBy('term');
    }
}
