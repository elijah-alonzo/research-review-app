<?php

namespace App\Filament\Resources\Subjects\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SubjectsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Subject Information')
                    ->columnSpanFull()
                    ->description('These are the details and information for the courses offered.')
                    ->schema([
                        Select::make('program_id')
                            ->label('Program')
                            ->relationship('program', 'name')
                            ->searchable()
                            ->preload()
                            ->prefixIcon('heroicon-m-academic-cap')
                            ->required(),

                        TextInput::make('code')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-m-tag')
                            ->placeholder('Enter subject code (e.g. GS-501)'),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-m-bookmark-square')
                            ->placeholder('Enter subject name'),

                        Textarea::make('description')
                            ->rows(3)
                            ->maxLength(65535)
                            ->placeholder('Optional subject description')
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->onIcon('heroicon-m-check-circle')
                            ->offIcon('heroicon-m-x-circle'),
                    ])
                    ->columns(2),
            ]);
    }
}
