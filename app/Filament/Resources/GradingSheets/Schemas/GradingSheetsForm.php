<?php

namespace App\Filament\Resources\GradingSheets\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GradingSheetsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
