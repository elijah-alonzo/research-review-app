<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Information')
                    ->columnSpanFull()
                    ->description('These are the details for the user account.')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('avatar')
                            ->label('Profile Picture')
                            ->image()
                            ->disk('public')
                            ->directory('avatars')
                            ->columnSpanFull(),

                        TextInput::make('name')
                            ->required()
                            ->prefixIcon('heroicon-m-user')
                            ->placeholder('Enter full name'),

                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->prefixIcon('heroicon-m-envelope')
                            ->placeholder('Enter email address'),

                        TextInput::make('contact_number')
                            ->label('Contact Number')
                            ->tel()
                            ->prefixIcon('heroicon-m-phone')
                            ->placeholder('Enter contact number'),

                        Select::make('role')
                            ->label('Role')
                            ->options([
                                'dean' => 'Dean (Admin)',
                                'faculty' => 'Faculty',
                            ])
                            ->prefixIcon('heroicon-m-shield-check')
                            ->required()
                            ->default('faculty'),

                        TextInput::make('password')
                            ->password()
                            ->required()
                            ->prefixIcon('heroicon-m-key')
                            ->placeholder('Enter password'),
                    ]),
            ]);
    }
}
