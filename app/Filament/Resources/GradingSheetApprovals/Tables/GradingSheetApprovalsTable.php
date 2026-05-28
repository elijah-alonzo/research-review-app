<?php

namespace App\Filament\Resources\GradingSheetApprovals\Tables;

use App\Models\Load;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GradingSheetApprovalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Grading Sheet Submissions')
            ->description('Review grading sheets and approve or reject submissions.')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Faculty')
                    ->getStateUsing(fn (Load $record): string => $record->user?->full_name ?? 'Unassigned')
                    ->searchable(),
                TextColumn::make('program.name')
                    ->label('Program')
                    ->searchable(),
                TextColumn::make('subject.name')
                    ->label('Subject')
                    ->searchable(),
                TextColumn::make('academicYear.year')
                    ->label('Academic Year')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('term')
                    ->label('Semester')
                    ->searchable(),
                TextColumn::make('grading_sheet_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'under_review'
                        ? 'Reviewing'
                        : str($state)->replace('_', ' ')->title()->toString())
                    ->color(fn (string $state): string => match ($state) {
                        'submitted' => 'success',
                        'under_review' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('submission_deadline')
                    ->label('Deadline')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->label('Uploaded At')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('program_id')
                    ->label('Program')
                    ->relationship('program', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('academic_year_id')
                    ->label('Academic Year')
                    ->relationship('academicYear', 'year')
                    ->searchable()
                    ->preload(),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->visible(fn (Load $record): bool => self::canModerate($record))
                        ->action(fn (Load $record) => $record->update([
                            'grading_sheet_status' => 'submitted',
                        ])),
                    Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-m-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (Load $record): bool => self::canModerate($record))
                        ->action(fn (Load $record) => $record->update([
                            'grading_sheet_status' => 'pending',
                        ])),
                    Action::make('download')
                        ->label('Download')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->visible(fn (Load $record): bool => $record->grading_sheet_status === 'submitted' && filled($record->grading_sheet))
                        ->action(fn (Load $record) => self::downloadGradingSheet($record)),
                ])
                    ->iconButton()
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->label('Actions'),
            ]);
    }

    protected static function downloadGradingSheet(Load $record)
    {
        if (! $record->grading_sheet) {
            return null;
        }

        return \Storage::disk('public')->download(
            $record->grading_sheet,
            basename($record->grading_sheet)
        );
    }

    protected static function canModerate(Load $record): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $record->grading_sheet_status === 'under_review'
            && $user->can('Update:GradingSheetApproval');
    }
}
