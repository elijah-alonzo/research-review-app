<?php

namespace App\Filament\Resources\Loads\Schemas;

use App\Enums\AcademicYear;
use App\Models\User;
use App\Models\Subject;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

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
                            ->required(fn (): bool => ! (Auth::user()?->isFaculty() ?? false))
                            ->disabled(fn (): bool => Auth::user()?->isFaculty() ?? false)
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
                            ->required(fn (): bool => ! (Auth::user()?->isFaculty() ?? false))
                            ->disabled(fn ($get): bool => blank($get('program_id')) || (Auth::user()?->isFaculty() ?? false)),

                        Select::make('term')
                            ->label('Term')
                            ->options([
                                'First Term' => 'First Term',
                                '2nd Term' => '2nd Term',
                                '3rd Term' => '3rd Term',
                            ])
                            ->prefixIcon('heroicon-m-calendar')
                            ->required(fn (): bool => ! (Auth::user()?->isFaculty() ?? false))
                            ->disabled(fn (): bool => Auth::user()?->isFaculty() ?? false),

                        Select::make('academic_year')
                            ->label('Academic Year')
                            ->options(AcademicYear::options())
                            ->default(AcademicYear::current()->value)
                            ->prefixIcon('heroicon-m-calendar-days')
                            ->required(fn (): bool => ! (Auth::user()?->isFaculty() ?? false))
                            ->disabled(fn (): bool => Auth::user()?->isFaculty() ?? false),

                        Select::make('user_id')
                            ->label('Faculty')
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search): array {
                                return User::query()
                                    ->where(function ($query) use ($search) {
                                        $query->where('first_name', 'like', "%{$search}%")
                                            ->orWhere('middle_initial', 'like', "%{$search}%")
                                            ->orWhere('last_name', 'like', "%{$search}%");
                                    })
                                    ->orderBy('last_name')
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(fn (User $user) => [$user->id => $user->full_name])
                                    ->all();
                            })
                            ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->full_name)
                            ->prefixIcon('heroicon-m-user')
                            ->required(fn (): bool => ! (Auth::user()?->isFaculty() ?? false))
                            ->disabled(fn (): bool => Auth::user()?->isFaculty() ?? false),

                        DateTimePicker::make('submission_deadline')
                            ->label('Submission Deadline')
                            ->prefixIcon('heroicon-m-calendar-days')
                            ->native(false)
                            ->required(fn (): bool => ! (Auth::user()?->isFaculty() ?? false))
                            ->disabled(fn (): bool => Auth::user()?->isFaculty() ?? false),


                        FileUpload::make('grading_sheet')
                            ->label('Grading Sheet File')
                            ->disk('public')
                            ->directory('grading-sheets')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'text/csv',
                            ])
                            ->maxSize(10 * 1024)
                            ->columnSpanfull(),
                    ])
                    ->columns(2),
            ]);
    }
}
