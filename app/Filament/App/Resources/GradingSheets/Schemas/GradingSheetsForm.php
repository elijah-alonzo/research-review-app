<?php

namespace App\Filament\App\Resources\GradingSheets\Schemas;

use App\Models\Load;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class GradingSheetsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                View::make('app.grading-sheets.status-tracker')
                    ->viewData(fn (?Load $record): array => [
                        'current' => $record?->grading_sheet_status ?? 'pending',
                    ])
                    ->columnSpanFull(),
                Section::make('Grading Sheet Upload')
                    ->columnSpanFull()
                    ->description('Upload the grading sheet file for this load.')
                    ->schema([
                        FileUpload::make('grading_sheet')
                            ->label('Grading Sheet File')
                            ->disk('public')
                            ->directory('grading-sheets')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'text/csv',
                            ])
                            ->maxSize(10 * 1024),
                    ]),
            ]);
    }
}
