<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->circular()
                    ->label(' ')
                    ->imageSize(40)
                    ->grow(false)
                    ->defaultImageUrl(fn (User $record): string => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&background=0F172A&color=FFFFFF'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('contact_number')
                    ->label('Contact Number')
                    ->icon('heroicon-m-phone')
                    ->placeholder('No contact number'),
                TextColumn::make('email')
                    ->label('Email Address')
                    ->icon('heroicon-m-envelope')
                    ->searchable(),
                BadgeColumn::make('role')
                    ->label('Role')
                    ->colors([
                        'danger' => 'dean',
                        'info' => 'faculty',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
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
