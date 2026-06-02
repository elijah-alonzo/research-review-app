<?php

namespace App\Filament\Resources\GradingSheets\Pages;

use App\Filament\Resources\GradingSheets\GradingSheetsResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditGradingSheet extends EditRecord
{
    protected static string $resource = GradingSheetsResource::class;

    protected ?string $subheading = 'Submit your grading sheet file.';

    protected ?string $previousStatus = null;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function beforeSave(): void
    {
        $this->previousStatus = $this->record->grading_sheet_status;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (array_key_exists('grading_sheet', $data) && filled($data['grading_sheet'])) {
            $data['grading_sheet_status'] = 'to_verify';
        }

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->record->grading_sheet_status !== 'to_verify') {
            return;
        }

        if ($this->previousStatus === 'to_verify') {
            return;
        }

        $recipients = $this->getReviewRecipients();

        if ($recipients->isEmpty()) {
            return;
        }

        Notification::make()
            ->title('Grading sheet submitted')
            ->body('A grading sheet has been submitted for verification.')
            ->sendToDatabase($recipients);
    }

    protected function getReviewRecipients()
    {
        return User::role(['Dean', 'Staff', 'Registrar'])->get();
    }
}
