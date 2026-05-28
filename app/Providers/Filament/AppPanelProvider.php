<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Widgets\AcademicContextWidget;
use App\Filament\Widgets\DashboardStatsWidget;
use App\Filament\Widgets\MyLoadStatsWidget;
use App\Filament\Widgets\MyPendingGradingSheetsWidget;
use App\Filament\Widgets\MyProgramSubjectsWidget;
use App\Filament\Widgets\MyRecentActivityWidget;
use App\Filament\Widgets\MyUpcomingDeadlinesWidget;
use App\Filament\Widgets\UnsubmittedGradingSheetsWidget;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('app')
            ->login(Login::class)
            ->passwordReset()
            ->emailVerification()
            ->emailChangeVerification()
            ->profile(null)
            ->darkmode(false)
            ->globalSearch(false)
            ->collapsibleNavigationGroups(false)
            ->viteTheme('resources/css/filament/app/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->navigationGroups([
                'Grading Sheet Management',
                'User Management',
                'Academic Management',
                'System Settings',
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AcademicContextWidget::class,
                MyLoadStatsWidget::class,
                MyPendingGradingSheetsWidget::class,
                MyUpcomingDeadlinesWidget::class,
                MyProgramSubjectsWidget::class,
                MyRecentActivityWidget::class,
                DashboardStatsWidget::class,
                UnsubmittedGradingSheetsWidget::class,
            ])
            ->plugin(FilamentShieldPlugin::make()->navigationGroup('System Settings'))
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->breadcrumbs(false)
            ->font('Figtree')
            ->brandLogo(asset('sys-logo.png'))
            ->brandLogoHeight('3rem');
    }
}
