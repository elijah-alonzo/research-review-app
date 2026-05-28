<?php

namespace App\Filament\Resources\GradingSheets\Pages;

use App\Filament\Resources\GradingSheets\GradingSheetsResource;
use Filament\Resources\Pages\EditRecord;

class EditGradingSheet extends EditRecord
{
    protected static string $resource = GradingSheetsResource::class;

    protected ?string $subheading = 'Submit your grading sheet file.';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (array_key_exists('grading_sheet', $data) && filled($data['grading_sheet'])) {
            $data['grading_sheet_status'] = 'under_review';
        }

        return $data;
    }
}
