<?php

namespace App\Filament\Resources\PendingGradingSheets\Pages;

use App\Filament\Resources\PendingGradingSheets\PendingGradingSheetsResource;
use App\Models\Load;
use BackedEnum;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class ViewPendingGradingSheetDetails extends ViewPendingGradingSheet
{
    protected static string $resource = PendingGradingSheetsResource::class;

    protected static ?string $navigationLabel = 'Details';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                View::make('filament.grading-sheets.status-tracker')
                    ->viewData([
                        'current' => $this->record->grading_sheet_status,
                    ])
                    ->columnSpanFull(),
                Section::make('Grading Sheet Details')
                    ->columns(1)
                    ->inlineLabel()
                    ->schema([
                        Placeholder::make('faculty')
                            ->label('Faculty:')
                            ->content(fn (Load $record): string => $record->user?->full_name ?? 'Unassigned'),
                        Placeholder::make('program')
                            ->label('Program:')
                            ->content(fn (Load $record): string => $record->program?->name ?? 'N/A'),
                        Placeholder::make('subject')
                            ->label('Subject:')
                            ->content(fn (Load $record): string => $record->subject?->name ?? 'N/A'),
                        Placeholder::make('semester')
                            ->label('Semester:')
                            ->content(fn (Load $record): string => (string) $record->term),
                        Placeholder::make('academic_year')
                            ->label('Academic Year:')
                            ->content(fn (Load $record): string => $record->academicYear?->year ?? 'N/A'),
                        Placeholder::make('status')
                            ->label('Status:')
                            ->badge()
                            ->content(fn (Load $record): string => str($record->submission_status)->title()->toString()),
                    ]),
            ]);
    }
}
