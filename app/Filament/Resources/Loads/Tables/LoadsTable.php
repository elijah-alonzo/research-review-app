<?php

namespace App\Filament\Resources\Loads\Tables;

use App\Enums\AcademicYear;
use App\Models\Load;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class LoadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Faculty Teaching Loads')
            ->description('A list of your teaching loads of all users in the system.')
            ->columns([
                ColumnGroup::make('Faculty', [
                    ImageColumn::make('user.avatar')
                        ->label('Picture')
                        ->circular()
                        ->imageSize(40)
                        ->getStateUsing(function (Load $record) {
                            $name = $record->user?->full_name ?? 'Faculty';
                            $avatar = $record->user?->avatar;

                            return $avatar
                                ? (str_starts_with($avatar, 'http')
                                    ? $avatar
                                    : asset('storage/'.$avatar))
                                : 'https://ui-avatars.com/api/?name='.urlencode($name).'&background=0F172A&color=FFFFFF';
                        }),
                    TextColumn::make('user_name')
                        ->label('Faculty')
                        ->weight('medium')
                        ->placeholder('Unassigned')
                        ->getStateUsing(fn (Load $record): string => $record->user?->full_name ?? 'Unassigned')
                        ->searchable(query: function (Builder $query, string $search) {
                            $query->whereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('middle_initial', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%");
                            });
                        }),
                ]),
                ColumnGroup::make('Course Information', [
                    TextColumn::make('program.name')
                        ->label('Program')
                        ->searchable(),
                    TextColumn::make('subject.name')
                        ->label('Subject')
                        ->searchable(),
                    TextColumn::make('academic_year')
                        ->label('Academic Year')
                        ->formatStateUsing(fn (AcademicYear|string|null $state): string => $state instanceof AcademicYear ? $state->value : (string) $state)
                        ->badge()
                        ->color('gray'),
                    TextColumn::make('term')
                        ->searchable(),
                ]),
                ColumnGroup::make('Submission Status', [
                    TextColumn::make('submission_status')
                        ->label('Status')
                        ->badge()
                        ->formatStateUsing(fn (string $state): string => ucfirst($state))
                        ->color(fn (string $state): string => $state === 'submitted' ? 'success' : 'gray'),
                    TextColumn::make('submission_deadline')
                        ->label('Deadline')
                        ->dateTime(),
                ]),
                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()->color('info'),
                    Action::make('download_grading_sheet')
                        ->label('Download')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->url(fn (Load $record): ?string => $record->grading_sheet
                            ? Storage::disk('public')->url($record->grading_sheet)
                            : null, true)
                        ->visible(fn (Load $record): bool => filled($record->grading_sheet)),
                    DeleteAction::make()
                        ->visible(fn (Load $record): bool => auth()->user()?->can('delete', $record) ?? false),
                ])
                    ->iconButton()
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->label('Actions'),
            ]);
    }
}
