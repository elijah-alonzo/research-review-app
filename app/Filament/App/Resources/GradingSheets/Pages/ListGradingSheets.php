<?php

namespace App\Filament\App\Resources\GradingSheets\Pages;

use App\Filament\App\Resources\GradingSheets\GradingSheetsResource;
use App\Models\Load;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ListGradingSheets extends Page
{
    protected static string $resource = GradingSheetsResource::class;

    protected string $view = 'app.grading-sheets.grading-sheets';

    public function getViewData(): array
    {
        $loads = Load::query()
            ->where('user_id', Auth::id())
            ->with(['program', 'subject', 'academicYear'])
            ->orderByDesc('academic_year_id')
            ->orderBy('term')
            ->get();

        return [
            'loads' => $loads,
        ];
    }
}
