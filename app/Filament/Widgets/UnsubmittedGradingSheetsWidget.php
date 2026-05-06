<?php

namespace App\Filament\Widgets;

use App\Models\Load;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UnsubmittedGradingSheetsWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->can('ViewDashboardStats') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Unsubmitted Grading Sheets')
            ->description('Loads that do not have a grading sheet file yet.')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Faculty')
                    ->getStateUsing(fn (Load $record): string => $record->user?->full_name ?? 'Unassigned')
                    ->searchable(),
                TextColumn::make('program.name')
                    ->label('Program')
                    ->searchable(),
                TextColumn::make('subject.name')
                    ->label('Course')
                    ->searchable(),
                TextColumn::make('term')
                    ->label('Term')
                    ->searchable(),
                TextColumn::make('submission_deadline')
                    ->label('Deadline')
                    ->dateTime(),
            ])
            ->query($this->getUnsubmittedQuery())
            ->paginated([5, 10, 25]);
    }

    protected function getUnsubmittedQuery(): Builder
    {
        return Load::query()
            ->whereNull('grading_sheet')
            ->with(['user', 'program', 'subject'])
            ->orderBy('submission_deadline');
    }
}
