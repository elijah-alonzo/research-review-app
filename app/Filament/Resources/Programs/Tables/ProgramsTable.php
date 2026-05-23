<?php

namespace App\Filament\Resources\Programs\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProgramsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Graduate School Programs')
            ->description('Overview of the university’s graduate programs, including key details and course offerings.')
            ->columns([
                TextColumn::make('code')
                    ->label('Program Code')
                    ->searchable()
                    ->badge()
                    ->icon('heroicon-m-academic-cap')
                    ->color('success'),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('subjects_count')
                    ->label('Courses')
                    ->counts('subjects')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('description')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Date Created')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()->color('info'),
                    DeleteAction::make(),
                ])
                    ->iconButton()
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->label('Actions'),
            ]);
    }
}
