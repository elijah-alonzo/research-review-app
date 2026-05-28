<?php

namespace App\Filament\Resources\Programs\Pages;

use App\Filament\Resources\Programs\ProgramsResource;
use App\Filament\Resources\Programs\Widgets\ProgramsStatsWidget;
use Filament\Actions\CreateAction;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Resources\Pages\ListRecords;

class ListPrograms extends ListRecords
{
    protected static string $resource = ProgramsResource::class;

    protected ?string $subheading = 'Browse, create, and manage graduate programs.';

    protected function getHeaderWidgets(): array
    {
        return [
            ProgramsStatsWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'doctoral' => Tab::make('Doctoral')
                ->modifyQueryUsing(fn ($query) => $query->where('degree', 'Doctoral')),
            'masteral' => Tab::make('Masteral')
                ->modifyQueryUsing(fn ($query) => $query->where('degree', 'Masteral')),
        ];
    }
}
