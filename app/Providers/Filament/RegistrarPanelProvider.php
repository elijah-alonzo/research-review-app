<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Registrar\RegistrarDashboard;
use App\Filament\Resources\GradingSheetApprovals\GradingSheetApprovalsResource;
use App\Http\Middleware\EnsureRegistrarRole;
use App\Http\Middleware\RedirectBasedOnRole;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class RegistrarPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('registrar')
            ->path('registrar')
            ->login()
            ->topNavigation()
            ->passwordReset()
            ->emailVerification()
            ->emailChangeVerification()
            ->sidebarCollapsibleOnDesktop()
            ->profile()
            ->darkmode(false)
            ->globalSearch(false)
            ->collapsibleNavigationGroups(false)
            ->viteTheme('resources/css/filament/app/theme.css')
            ->pages([
                RegistrarDashboard::class,
            ])
            ->resources([
                GradingSheetApprovalsResource::class,
            ])
            ->navigationGroups([
                'Grading Sheets',
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                RedirectBasedOnRole::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authMiddleware([
                EnsureRegistrarRole::class,
            ])
            ->breadcrumbs(false)
            ->font('Figtree')
            ->brandLogo(asset('sys-logo.png'))
            ->brandLogoHeight('3rem')
            ->topbar(true)
            ->sidebarWidth('16rem');
    }
}
