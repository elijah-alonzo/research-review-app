<?php

namespace App\Filament\Widgets;

use App\Models\Load;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class MyPendingGradingSheetsWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->can('View:MyPendingGradingSheetsWidget') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('My Pending Grading Sheets')
            ->description('Loads that still need a grading sheet file.')
            ->columns([
                TextColumn::make('program.name')
                    ->label('Program')
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-m-academic-cap')
                    ->searchable(),
                TextColumn::make('subject.name')
                    ->label('Course')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-book-open')
                    ->searchable(),
                TextColumn::make('term')
                    ->label('Term')
                    ->searchable(),
                TextColumn::make('submission_deadline')
                    ->label('Deadline')
                    ->dateTime(),
            ])
            ->query($this->getPendingQuery())
            ->paginated([5, 10, 25]);
    }

    protected function getPendingQuery(): Builder
    {
        return Load::query()
            ->where('user_id', auth()->id())
            ->whereNull('grading_sheet')
            ->with(['program', 'subject'])
            ->orderBy('submission_deadline');
    }
}
