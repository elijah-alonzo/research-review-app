<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('login')
            ->label('Email or Contact Number')
            ->required()
            ->autocomplete()
            ->autofocus();
    }

    protected function getFormComponents(): array
    {
        return [
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getRememberFormComponent(),
            Select::make('panel')
                ->label('Select Panel')
                ->options([
                    'app' => 'Graduate School',
                    'faculty' => 'Faculty',
                    'registrar' => 'Registrar',
                ])
                ->required()
                ->native(false)
                ->default('app')
                ->helperText('Choose which panel you want to access'),
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        $value = trim((string) ($data['login'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        // Store selected panel in session for post-login redirect
        session(['selected_panel' => $data['panel'] ?? 'app']);

        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return ['email' => $value, 'password' => $password];
        }

        return ['contact_number' => $value, 'password' => $password];
    }

    protected function getAuthenticatedRedirectUrl(): string
    {
        $user = Auth::user();
        $selectedPanel = session()->pull('selected_panel', 'app');

        // Validate user has access to selected panel
        $canAccessFaculty = $user->hasRole('Faculty');
        $canAccessRegistrar = $user->hasRole('Registrar');
        $canAccessApp = $user->hasAnyRole(['Admin', 'Dean', 'Staff']);

        // Check if user can access selected panel
        $isValidSelection = match ($selectedPanel) {
            'faculty' => $canAccessFaculty,
            'registrar' => $canAccessRegistrar,
            'app' => $canAccessApp,
            default => false,
        };

        // If selection is invalid, redirect to default allowed panel
        if (! $isValidSelection) {
            if ($canAccessFaculty) {
                return '/faculty';
            } elseif ($canAccessRegistrar) {
                return '/registrar';
            } elseif ($canAccessApp) {
                return '/app';
            }
        }

        // Redirect to selected panel
        return match ($selectedPanel) {
            'faculty' => '/faculty',
            'registrar' => '/registrar',
            default => '/app',
        };
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.login' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }
}
