@php
    $user = auth()->user();
    $initials = collect([$user->first_name, $user->last_name])
        ->map(fn ($name) => str($name)->substr(0, 1)->upper())
        ->implode('');
@endphp

@include('app.home.styles')

<div class="home-dashboard-container">

    {{-- ── Left: Profile Card ── --}}
    <div>
        <div class="profile-card">
            <div class="profile-banner"></div>
            <div class="profile-body">
                <div class="profile-avatar-wrapper">
                    @if ($user->avatar)
                        <img src="{{ Storage::disk('public')->url($user->avatar) }}" alt="{{ $user->full_name }}" class="profile-avatar-img" />
                    @else
                        <div class="profile-avatar-gradient">{{ $initials }}</div>
                    @endif
                </div>

                <h2 class="profile-name">{{ $user->full_name }}</h2>
                <span class="profile-role-badge">{{ $user->roles->first()?->name ?? 'Faculty Member' }}</span>

                <div class="profile-details-list">
                    <div class="profile-detail-item" title="{{ $user->email }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="profile-detail-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                        <span class="profile-detail-text">{{ $user->email }}</span>
                    </div>

                    <div class="profile-detail-item">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="profile-detail-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>
                        <span class="profile-detail-text">{{ $user->contact_number ?? 'No contact info' }}</span>
                    </div>

                    <div class="profile-detail-item" title="{{ $user->program?->name ?? 'No assigned program' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="profile-detail-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.9m10.14-10.753a60.47 60.47 0 0 1-.49 6.348m-9.65-11.897L12 3l7.98 4.417m-15.96 0L12 7.417m0 0v8m0 0l-7.98-4.417M12 15.417l7.98-4.417" />
                        </svg>
                        <span class="profile-detail-text">{{ $user->program?->code ?? 'N/A' }} — {{ $user->program?->name ?? 'Unassigned Program' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Right: Grading Sheets List ── --}}
    <div class="list-section-col">
        <div class="section-header">
            <div>
                <span class="section-title">My Grading Sheets</span>
                <p class="section-subtitle">Track and manage your grading sheet submissions.</p>
            </div>
        </div>

        <div class="grading-sheets-grid">
    @forelse ($loads->filter(fn ($l) => $l->grading_sheet_status !== 'submitted') as $load)
        <div class="grading-sheet-card">
            <div class="grading-sheet-card-header">
                <div style="min-width:0; flex:1">
                    <p class="text-sm font-semibold text-gray-950 dark:text-white truncate">
                        {{ $load->subject->name }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                        {{ $load->program->name }} • {{ $load->term }}
                    </p>
                </div>
                <div class="grading-sheet-card-header-actions">
                    @if ($load->grading_sheet_status === 'pending')
                        <a href="{{ \App\Filament\App\Resources\GradingSheets\GradingSheetsResource::getUrl('edit', ['record' => $load]) }}" class="cta-button upload-btn">
                            <x-filament::icon icon="heroicon-m-arrow-up-tray" class="btn-icon" />
                            Upload
                        </a>
                    @else
                        <a href="{{ \App\Filament\App\Resources\GradingSheets\GradingSheetsResource::getUrl('view', ['record' => $load]) }}" class="cta-button view-btn">
                            <x-filament::icon icon="heroicon-m-eye" class="btn-icon" />
                            View
                        </a>
                    @endif
                </div>
            </div>

            <div class="grading-sheet-card-body">
                @include('public.progress', [
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
                <h3 class="empty-state-title">You're all caught up</h3>
                <p class="empty-state-description">
                    No grading sheets are available at the moment. Once teaching
                    loads are assigned and grading sheets are generated, they
                    will automatically appear here.
                </p>
            </div>
        </div>
    @endforelse
</div>

</div>