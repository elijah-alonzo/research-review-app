<x-filament-widgets::widget>
    <style>
        .grading-sheets-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .grading-sheet-card {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .dark .grading-sheet-card {
            background: rgb(17 24 39);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .grading-sheet-card-header {
            padding: 1rem 1.25rem;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .grading-sheet-card-body {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
        }

        .dark .grading-sheet-card-body {
            border-top-color: rgba(255, 255, 255, 0.05);
        }
    </style>

    <div class="grading-sheets-grid">
        @forelse ($loads as $load)
            <div class="grading-sheet-card">
                <div class="grading-sheet-card-header">
                    <div style="min-width:0; flex:1">
                        <p class="text-sm font-medium text-gray-950 dark:text-white truncate">
                            {{ $load->subject->name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ $load->program->name }} • {{ $load->term }}
                        </p>
                    </div>
                    <x-filament::badge color="gray" class="shrink-0">
                        {{ $load->academicYear->year }}
                    </x-filament::badge>
                </div>

                <div class="grading-sheet-card-body">
                    @include('filament.grading-sheets.status-tracker', ['current' => $load->grading_sheet_status])
                </div>
            </div>
        @empty
            <div class="fi-section rounded-xl ring-1 ring-gray-950/5 dark:ring-white/10 bg-white dark:bg-gray-900 py-10 flex flex-col items-center justify-center gap-2">
                <x-filament::icon
                    icon="heroicon-o-document-text"
                    class="h-8 w-8 text-gray-400 dark:text-gray-500"
                />
                <p class="text-sm text-gray-500 dark:text-gray-400">No grading sheets found.</p>
            </div>
        @endforelse
    </div>
</x-filament-widgets::widget>