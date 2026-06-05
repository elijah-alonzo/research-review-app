<?php

namespace App\Filament\Admin\Resources\Account;

use App\Filament\Admin\Resources\Account\Pages\EditAccount;
use App\Filament\Admin\Resources\Account\Pages\ViewAccount;
use App\Models\User;
use BackedEnum;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class AccountResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'account';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Account Settings';

    protected static UnitEnum|string|null $navigationGroup = 'System Settings';

    protected static ?int $navigationSort = 30;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return auth()->check();
    }

    public static function getNavigationUrl(): string
    {
        $userId = auth()->id();

        if (! $userId) {
            return '#';
        }

        return static::getUrl('view', ['record' => $userId]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $userId = auth()->id();

        if (! $userId) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereKey($userId);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->id() === $record->getKey();
    }

    public static function canView(Model $record): bool
    {
        return auth()->id() === $record->getKey();
    }

    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    public static function getPages(): array
    {
        return [
            'view' => ViewAccount::route('/{record}'),
            'edit' => EditAccount::route('/{record}/edit'),
        ];
    }

    /**
     * @return array<NavigationItem>
     */
    public static function getNavigationItems(): array
    {
        if (! static::shouldRegisterNavigation() || ! static::canAccess()) {
            return [];
        }

        return [
            NavigationItem::make(static::getNavigationLabel())
                ->group(static::getNavigationGroup())
                ->icon(static::getNavigationIcon())
                ->isActiveWhen(fn (): bool => request()->routeIs(static::getRouteBaseName().'.*'))
                ->url(static::getNavigationUrl()),
        ];
    }
}
