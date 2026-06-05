<?php

namespace App\Filament\Admin\Resources\GradingSheets\Pages;

use App\Filament\Admin\Resources\GradingSheets\GradingSheetsResource;
use App\Models\Load;
use BackedEnum;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class ViewGradingSheetDetails extends ViewRecord
{
    protected static string $resource = GradingSheetsResource::class;

    protected static ?string $navigationLabel = 'Details';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document';

    protected ?string $subheading = 'Review the grading sheet details.';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                View::make('app.grading-sheets.status-tracker')
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
