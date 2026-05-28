<?php

namespace App\Filament\Resources\GradingSheets\Tables;

use App\Enums\AcademicYear;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GradingSheetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Grading Sheets')
            ->description('A list of your teaching loads where you can upload grading sheets.')
            ->columns([
                ColumnGroup::make('Course Information', [
                    TextColumn::make('program.name')
                        ->label('Program')
                        ->searchable(),
                    TextColumn::make('subject.name')
                        ->label('Subject')
                        ->searchable(),
                    TextColumn::make('academic_year')
                        ->label('Academic Year')
                        ->formatStateUsing(fn (AcademicYear|string|null $state): string => $state instanceof AcademicYear ? $state->value : (string) $state)
                        ->badge()
                        ->color('gray'),
                    TextColumn::make('term')
                        ->label('Semester')
                        ->searchable(),
                ]),
                ColumnGroup::make('Submission Status', [
                    TextColumn::make('submission_status')
                        ->label('Status')
                        ->badge()
                        ->formatStateUsing(fn (string $state): string => ucfirst($state))
                        ->color(fn (string $state): string => $state === 'submitted' ? 'success' : 'gray'),
                    TextColumn::make('submission_deadline')
                        ->label('Deadline')
                        ->dateTime(),
                ]),
                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Upload')
                        ->icon('heroicon-m-arrow-up-tray')
                        ->color('info'),
                ])
                    ->iconButton()
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->label('Actions'),
            ]);
    }
}
