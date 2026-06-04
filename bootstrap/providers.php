<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\Filament\AppPanelProvider;
use App\Providers\Filament\FacultyPanelProvider;
use App\Providers\Filament\RegistrarPanelProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    AppPanelProvider::class,
    FacultyPanelProvider::class,
    RegistrarPanelProvider::class,
];
