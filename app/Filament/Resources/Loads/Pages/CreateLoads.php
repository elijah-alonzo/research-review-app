<?php

namespace App\Filament\Resources\Loads\Pages;

use App\Filament\Resources\Loads\LoadsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateLoads extends CreateRecord
{
    protected static string $resource = LoadsResource::class;

    protected ?string $subheading = 'Create a new faculty load record.';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['submission_deadline'])) {
            $data['submission_deadline'] = Carbon::now()->addDays(30);
        }

        return $data;
    }
}
