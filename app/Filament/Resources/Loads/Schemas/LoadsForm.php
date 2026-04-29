<?php

namespace App\Filament\Resources\Loads\Schemas;

use App\Enums\AcademicYear;
use App\Models\Subject;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LoadsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Load Details')
                    ->columnSpanFull()
                    ->description('These are the details for the teaching load assignment.')
                    ->schema([
                        Select::make('program_id')
                            ->label('Program')
                            ->relationship('program', 'name')
                            ->searchable()
                            ->preload()
                            ->prefixIcon('heroicon-m-academic-cap')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($set): void {
                                $set('subject_id', null);
                            }),

                        Select::make('subject_id')
                            ->label('Subject')
                            ->options(fn ($get) => Subject::query()
                                ->where('program_id', $get('program_id'))
                                ->orderBy('name')
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->prefixIcon('heroicon-m-book-open')
                            ->required()
                            ->disabled(fn ($get) => blank($get('program_id'))),

                        Select::make('term')
                            ->label('Term')
                            ->options([
                                'First Term' => 'First Term',
                                '2nd Term' => '2nd Term',
                                '3rd Term' => '3rd Term',
                            ])
                            ->prefixIcon('heroicon-m-calendar')
                            ->required(),

                        Select::make('academic_year')
                            ->label('Academic Year')
                            ->options(AcademicYear::options())
                            ->default(AcademicYear::current()->value)
                            ->prefixIcon('heroicon-m-calendar-days')
                            ->required(),

                        Select::make('user_id')
                            ->label('Faculty')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->prefixIcon('heroicon-m-user')
                            ->required(),

                        Toggle::make('is_submitted')
                            ->label('Submitted')
                            ->default(false)
                            ->onIcon('heroicon-m-check-circle')
                            ->offIcon('heroicon-m-x-circle'),

                        Select::make('submission_status')
                            ->label('Submission Status')
                            ->options([
                                'pending' => 'Pending',
                                'submitted' => 'Submitted',
                                'late' => 'Late',
                            ])
                            ->default('pending')
                            ->prefixIcon('heroicon-m-flag')
                            ->required(),

                        DateTimePicker::make('submission_deadline')
                            ->label('Submission Deadline')
                            ->prefixIcon('heroicon-m-calendar-days')
                            ->native(false)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
