<?php

namespace App\Filament\Widgets;

use App\Models\Load;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class MyUpcomingDeadlinesWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->can('View:MyUpcomingDeadlinesWidget') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('My Upcoming Deadlines')
            ->description('Deadlines in the next 14 days.')
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
            ->query($this->getUpcomingQuery())
            ->paginated([5, 10, 25]);
    }

    protected function getUpcomingQuery(): Builder
    {
        return Load::query()
            ->where('user_id', auth()->id())
            ->whereNull('grading_sheet')
            ->whereBetween('submission_deadline', [now(), now()->addDays(14)])
            ->with(['program', 'subject'])
            ->orderBy('submission_deadline');
    }
}
