<?php

namespace App\Filament\Widgets;

use App\Models\Load;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class MyProgramSubjectsWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->can('View:MyProgramSubjectsWidget') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('My Programs and Subjects')
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
                TextColumn::make('academic_year')
                    ->label('Academic Year')
                    ->searchable(),
                TextColumn::make('term')
                    ->label('Term')
                    ->searchable(),
            ])
            ->query($this->getProgramSubjectsQuery())
            ->paginated([5, 10, 25]);
    }

    protected function getProgramSubjectsQuery(): Builder
    {
        return Load::query()
            ->where('user_id', auth()->id())
            ->with(['program', 'subject'])
            ->orderByDesc('academic_year')
            ->orderBy('term');
    }
}
