<?php

namespace App\Filament\Resources\SystemLogs\Pages;

use App\Filament\Resources\SystemLogs\SystemLogsResource;
use Filament\Resources\Pages\ListRecords;

class ListSystemLogs extends ListRecords
{
    protected static string $resource = SystemLogsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
