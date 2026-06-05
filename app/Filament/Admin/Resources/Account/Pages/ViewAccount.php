<?php

namespace App\Filament\Admin\Resources\Account\Pages;

use App\Filament\Admin\Resources\Account\AccountResource;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewAccount extends ViewRecord
{
    protected static string $resource = AccountResource::class;

    public function getTitle(): string
    {
        return 'Account Management';
    }

    protected ?string $subheading = 'Your personal account and information management page.';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                $this->getAccountInformationSection(),
            ]);
    }

    protected function getAccountInformationSection(): Section
    {
        return Section::make('Account Information')
            ->description('Update your profile details.')
            ->columnSpanFull()
            ->columns(3)
            ->schema([
                FileUpload::make('avatar')
                    ->label('Profile Picture')
                    ->image()
                    ->disk('public')
                    ->directory('avatars')
                    ->columnSpanFull(),

                TextInput::make('first_name')
                    ->label('First Name')
                    ->required(),

                TextInput::make('middle_initial')
                    ->label('Middle Initial')
                    ->maxLength(1),

                TextInput::make('last_name')
                    ->label('Last Name')
                    ->required(),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->columnSpan(3),

                TextInput::make('contact_number')
                    ->label('Contact Number')
                    ->tel()
                    ->required()
                    ->columnSpan(3),
            ]);
    }
}
