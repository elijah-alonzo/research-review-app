<?php

declare(strict_types=1);

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    protected ?string $subheading = 'View details and permissions for this role.';

    protected function getActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
