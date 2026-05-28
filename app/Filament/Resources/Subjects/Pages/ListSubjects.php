<?php

namespace App\Filament\Resources\Subjects\Pages;

use App\Filament\Resources\Subjects\SubjectsResource;
use App\Filament\Resources\Subjects\Widgets\SubjectsStatsWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubjects extends ListRecords
{
    protected static string $resource = SubjectsResource::class;

    protected ?string $subheading = 'Browse, create, and manage subjects offered.';

    protected function getHeaderWidgets(): array
    {
        return [
            SubjectsStatsWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
