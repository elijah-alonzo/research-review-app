<?php

namespace App\Filament\Resources\Programs\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProgramsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Program Information')
                    ->description('Program details and settings.')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Enter program code (e.g. MIT)'),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter program name'),

                        Textarea::make('description')
                            ->rows(3)
                            ->maxLength(65535)
                            ->placeholder('Optional program description')
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
