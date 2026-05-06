<?php

namespace App\Filament\Resources\Loads\Pages;

use App\Filament\Resources\Loads\LoadsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListLoads extends ListRecords
{
    protected static string $resource = LoadsResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()?->isDean()) {
            return [
                CreateAction::make(),
            ];
        }

        return [];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();
        $user = Auth::user();

        // Faculty users can only see their own loads
        if ($user && $user->isFaculty()) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }
}
