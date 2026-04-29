<?php

namespace App\Filament\Resources\Programs\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'subjects';

    protected static ?string $title = 'Courses';

    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->description('These are the courses offered under this program.')
            ->columns([
                TextColumn::make('code')
                    ->label('Course Code')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Course')
                    ->searchable(),
                TextColumn::make('description')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
