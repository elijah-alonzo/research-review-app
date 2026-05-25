<?php

namespace App\Filament\Widgets;

use App\Models\SystemLog;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class MyRecentActivityWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->can('View:MyRecentActivityWidget') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('My Recent Activity')
            ->columns([
                TextColumn::make('description')
                    ->label('Activity')
                    ->getStateUsing(fn (SystemLog $record): string => $record->description ?: ($record->model_type ?? 'Activity'))
                    ->limit(60),
                TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->color('info'),
                TextColumn::make('created_at')
                    ->label('When')
                    ->dateTime(),
            ])
            ->query($this->getRecentActivityQuery())
            ->paginated([5, 10, 25]);
    }

    protected function getRecentActivityQuery(): Builder
    {
        return SystemLog::query()
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at');
    }
}
