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
                        <x-filament::icon icon="heroicon-o-envelope" class="profile-detail-icon" />
                        <span class="profile-detail-text">{{ $user->email }}</span>
                    </div>

                    <div class="profile-detail-item">
                        <x-filament::icon icon="heroicon-o-phone" class="profile-detail-icon" />
                        <span class="profile-detail-text">{{ $user->contact_number ?? 'No contact info' }}</span>
                    </div>

                    <div class="profile-detail-item" title="{{ $user->program?->name ?? 'No assigned program' }}">
                        <x-filament::icon icon="heroicon-o-academic-cap" class="profile-detail-icon" />
                        <span class="profile-detail-text">{{ $user->program?->name ?? 'Unassigned Program' }}</span>
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