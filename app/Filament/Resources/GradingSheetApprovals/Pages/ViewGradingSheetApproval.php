<?php

namespace App\Filament\Resources\GradingSheetApprovals\Pages;

use App\Filament\Resources\GradingSheetApprovals\GradingSheetApprovalsResource;
use App\Filament\Resources\GradingSheetApprovals\Tables\GradingSheetApprovalsTable;
use App\Models\Load;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ViewGradingSheetApproval extends ViewRecord
{
    protected static string $resource = GradingSheetApprovalsResource::class;

    protected ?string $subheading = 'Review the submitted grading sheet and approve or reject it.';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-m-check-circle')
                ->color('success')
                ->visible(fn (): bool => $this->canModerate())
                ->action(function (): void {
                    $this->record->update([
                        'grading_sheet_status' => 'submitted',
                    ]);

                    GradingSheetApprovalsTable::notifyStatusChange($this->record, 'approved');

                    $this->redirect(static::getResource()::getUrl('index'));
                }),
            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-m-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->canModerate())
                ->action(function (): void {
                    $this->record->update([
                        'grading_sheet_status' => 'pending',
                    ]);

                    GradingSheetApprovalsTable::notifyStatusChange($this->record, 'rejected');

                    $this->redirect(static::getResource()::getUrl('index'));
                }),
            Action::make('download')
                ->label('Download')
                ->icon('heroicon-m-arrow-down-tray')
                ->visible(fn (): bool => $this->record->grading_sheet_status === 'submitted'
                    && filled($this->record->grading_sheet))
                ->action(fn () => $this->downloadGradingSheet()),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Grading Sheet Details')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('faculty')
                            ->label('Faculty')
                            ->content(fn (Load $record): string => $record->user?->full_name ?? 'Unassigned'),
                        Placeholder::make('program')
                            ->label('Program')
                            ->content(fn (Load $record): string => $record->program?->name ?? 'N/A'),
                        Placeholder::make('subject')
                            ->label('Subject')
                            ->content(fn (Load $record): string => $record->subject?->name ?? 'N/A'),
                        Placeholder::make('semester')
                            ->label('Semester')
                            ->content(fn (Load $record): string => (string) $record->term),
                        Placeholder::make('academic_year')
                            ->label('Academic Year')
                            ->content(fn (Load $record): string => $record->academicYear?->year ?? 'N/A'),
                        Placeholder::make('status')
                            ->label('Status')
                            ->content(fn (Load $record): string => $record->submission_status),
                    ]),
                Section::make('Grading Sheet File')
                    ->schema([
                        Placeholder::make('grading_sheet_preview')
                            ->label('Preview')
                            ->content(fn (Load $record): HtmlString => $this->renderPreview($record)),
                    ]),
            ]);
    }

    protected function renderPreview(Load $record): HtmlString
    {
        if (! $record->grading_sheet) {
            return new HtmlString('No grading sheet file is available.');
        }

        $url = Storage::disk('public')->url($record->grading_sheet);
        $extension = Str::lower(pathinfo($record->grading_sheet, PATHINFO_EXTENSION));

        if ($extension === 'pdf') {
            return new HtmlString(
                '<iframe src="'.$url.'" style="width:100%; height:700px; border:0;" title="Grading Sheet"></iframe>'
            );
        }

        return new HtmlString('<a href="'.$url.'" target="_blank" rel="noopener">Open grading sheet</a>');
    }

    protected function canModerate(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $this->record->grading_sheet_status === 'under_review'
            && $user->can('Update:GradingSheetApproval');
    }

    protected function downloadGradingSheet()
    {
        if (! $this->record->grading_sheet) {
            return null;
        }

        return Storage::disk('public')->download(
            $this->record->grading_sheet,
            basename($this->record->grading_sheet)
        );
    }
}
