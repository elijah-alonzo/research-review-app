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
                    ->schema([
                        FileUpload::make('avatar')
                            ->label('Avatar')
                            ->avatar()
                            ->image()
                            ->disk('public')
                            ->directory('avatars')
                            ->columnSpanFull(),

                        TextInput::make('name')
                            ->required()
                            ->placeholder('Enter full name'),

                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->placeholder('Enter email address'),

                        TextInput::make('contact_number')
                            ->label('Contact Number')
                            ->tel()
                            ->placeholder('Enter contact number'),

                        TextInput::make('password')
                            ->password()
                            ->required()
                            ->placeholder('Enter password'),

                        Select::make('role')
                            ->label('Role')
                            ->options([
                                'dean' => 'Dean (Admin)',
                                'faculty' => 'Faculty',
                            ])
                            ->required()
                            ->default('faculty'),
                    ])
                    ->columns(1),
            ]);
    }
}
