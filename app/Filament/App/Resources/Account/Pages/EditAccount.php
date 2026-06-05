<?php

namespace App\Filament\App\Resources\Account\Pages;

use App\Filament\App\Resources\Account\AccountResource;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EditAccount extends EditRecord
{
    protected static string $resource = AccountResource::class;

    public function getTitle(): string
    {
        return 'Account Management';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                $this->getAccountInformationSection(),
                $this->getSecuritySection(),
            ]);
    }

    protected function getAccountInformationSection(): Section
    {
        return Section::make('Account Information')
            ->description('Update your profile details.')
            ->columns(3)
            ->schema([
                FileUpload::make('avatar')
                    ->label('Profile Picture')
                    ->image()
                    ->disk('public')
                    ->directory('avatars')
                    ->columnSpanFull(),

                TextInput::make('first_name')
                    ->label('First Name')
                    ->required(),

                TextInput::make('middle_initial')
                    ->label('Middle Initial')
                    ->maxLength(1),

                TextInput::make('last_name')
                    ->label('Last Name')
                    ->required(),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->live(debounce: 500)
                    ->columnSpan(3),

                TextInput::make('contact_number')
                    ->label('Contact Number')
                    ->tel()
                    ->required()
                    ->columnSpan(3),
            ]);
    }

    protected function getSecuritySection(): Section
    {
        return Section::make('Security & Password')
            ->description('Update your password.')
            ->columns(2)
            ->collapsible()
            ->collapsed()
            ->schema([
                TextInput::make('password')
                    ->label('New Password')
                    ->password()
                    ->autocomplete('new-password')
                    ->revealable(filament()->arePasswordsRevealable())
                    ->rule(Password::default()->uncompromised())
                    ->dehydrated(fn ($state): bool => filled($state))
                    ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                    ->different('currentPassword')
                    ->same('passwordConfirmation'),

                TextInput::make('passwordConfirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->autocomplete('new-password')
                    ->revealable(filament()->arePasswordsRevealable())
                    ->required(fn (Get $get): bool => filled($get('password')))
                    ->dehydrated(false),

                TextInput::make('currentPassword')
                    ->label('Current Password')
                    ->password()
                    ->autocomplete('current-password')
                    ->currentPassword(guard: Filament::getAuthGuard())
                    ->revealable(filament()->arePasswordsRevealable())
                    ->required(fn (Get $get): bool => filled($get('password')) || ($get('email') !== $this->getRecord()->getAttributeValue('email')))
                    ->helperText('Required to change email or password.')
                    ->dehydrated(false)
                    ->columnSpanFull(),
            ]);
    }
}
