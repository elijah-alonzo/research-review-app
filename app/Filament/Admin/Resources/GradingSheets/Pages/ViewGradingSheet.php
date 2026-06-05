<?php

namespace App\Filament\Admin\Resources\GradingSheets\Pages;

use App\Filament\Admin\Resources\GradingSheets\GradingSheetsResource;
use App\Models\Load;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ViewGradingSheet extends ViewRecord
{
    protected static string $resource = GradingSheetsResource::class;

    protected static ?string $navigationLabel = 'Preview';

    protected ?string $subheading = 'Review the grading sheet before uploading a new file.';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label('Upload')
                ->icon('heroicon-m-arrow-up-tray')
                ->color('info')
                ->visible(fn (): bool => $this->record->grading_sheet_status === 'pending')
                ->url(fn (): string => static::getResource()::getUrl('edit', ['record' => $this->record])),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                View::make('app.grading-sheets.status-tracker')
                    ->viewData([
                        'current' => $this->record->grading_sheet_status,
                    ])
                    ->columnSpanFull(),
                Placeholder::make('grading_sheet_preview')
                    ->label('Grading Sheet Preview')
                    ->columnSpanFull()
                    ->content(fn (Load $record): HtmlString => $this->renderPreview($record)),
            ]);
    }

    protected function renderPreview(Load $record): HtmlString
    {
        if (! $record->grading_sheet) {
            return new HtmlString('No grading sheet file is available.');
        }

        $url = Storage::disk('public')->url($record->grading_sheet);
        $extension = Str::lower(pathinfo($record->grading_sheet, PATHINFO_EXTENSION));

        if ($extension === 'pdf') {
            return new HtmlString(
                '<iframe src="'.$url.'#toolbar=0&navpanes=0&scrollbar=0" style="width:100%; height:700px; border:0;" title="Grading Sheet"></iframe>'
            );
        }

        return new HtmlString('<a href="'.$url.'" target="_blank" rel="noopener">Open grading sheet</a>');
    }
}
