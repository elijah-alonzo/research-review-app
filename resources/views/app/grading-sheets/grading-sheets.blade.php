<x-filament-panels::page>
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
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
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

        .empty-state {
            background: white;
            border: 1px dashed rgba(0, 0, 0, 0.12);
            border-radius: 1rem;
            padding: 4rem 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .dark .empty-state {
            background: rgb(17 24 39);
            border-color: rgba(255, 255, 255, 0.12);
        }

        .empty-state-content {
            max-width: 420px;
            text-align: center;
        }

        .empty-state-icon-wrapper {
            width: 72px;
            height: 72px;
            margin: 0 auto;
            border-radius: 20px;
            background: rgba(99, 102, 241, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dark .empty-state-icon-wrapper {
            background: rgba(99, 102, 241, 0.15);
        }

        .empty-state-icon {
            width: 36px;
            height: 36px;
            color: rgb(99, 102, 241);
        }

        .empty-state-title {
            margin: 1.25rem 0 0.5rem;
            font-size: 1.125rem;
            font-weight: 600;
            color: rgb(17, 24, 39);
        }

        .dark .empty-state-title {
            color: white;
        }

        .empty-state-description {
            margin: 0;
            font-size: 0.9rem;
            line-height: 1.6;
            color: rgb(107, 114, 128);
        }

        .dark .empty-state-description {
            color: rgb(156, 163, 175);
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
                    @include('filament.grading-sheets.status-tracker', [
                        'current' => $load->grading_sheet_status
                    ])
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-content">
                    <div class="empty-state-icon-wrapper">
                        <x-filament::icon
                            icon="heroicon-o-clipboard-document-list"
                            class="empty-state-icon"
                        />
                    </div>

                    <h3 class="empty-state-title">
                        You're all caught up
                    </h3>

                    <p class="empty-state-description">
                        No grading sheets are available at the moment. Once teaching
                        loads are assigned and grading sheets are generated, they
                        will automatically appear here.
                    </p>
                </div>
            </div>
        @endforelse
    </div>
</x-filament-panels::page>