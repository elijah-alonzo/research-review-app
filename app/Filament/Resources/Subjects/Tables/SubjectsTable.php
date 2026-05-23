<?php

namespace App\Filament\Resources\Subjects\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Offered Courses')
            ->description('Detailed listing of courses available in the university’s graduate programs.')
            ->columns([
                TextColumn::make('code')
                    ->label('Course Code')
                    ->icon('heroicon-m-book-open')
                    ->searchable()
                    ->badge()
                    ->color('success'),
                TextColumn::make('name')
                    ->label('Course')
                    ->searchable(),
                TextColumn::make('program.name')
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-m-academic-cap')
                    ->label('Program')
                    ->searchable(),
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
