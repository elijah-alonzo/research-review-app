<?php

namespace App\Filament\Resources\Loads\Pages;

use App\Filament\Resources\Loads\LoadsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class ListLoads extends ListRecords
{
    protected static string $resource = LoadsResource::class;

    protected ?string $subheading = 'Browse, create, and manage faculty loads.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn (): bool => static::getResource()::canCreate()),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();
        $user = Auth::user();

        if (! $user || ! static::getResource()::canViewAny()) {
            return $query->whereRaw('1 = 0');
        }

        return $query->with(['program', 'subject', 'user', 'academicYear']);
    }

}
