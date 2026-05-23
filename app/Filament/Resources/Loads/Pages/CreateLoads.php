<?php

namespace App\Filament\Resources\Loads\Pages;

use App\Filament\Resources\Loads\LoadsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLoads extends CreateRecord
{
    protected static string $resource = LoadsResource::class;

    protected ?string $subheading = 'Create a new faculty load record.';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
