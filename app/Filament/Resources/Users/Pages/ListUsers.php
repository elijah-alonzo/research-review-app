<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Users\Widgets\UsersStatsWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected ?string $subheading = 'Browse, create, and manage users.';

    protected function getHeaderWidgets(): array
    {
        return [
            UsersStatsWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
