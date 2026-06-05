@php
    $changes = $changes ?? [];
    $record = $record ?? null;
    $formatValue = function ($value): string {
        if ($value === null) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return json_encode($value) ?: '';
    };
@endphp

@if ($record)
    <div class="mb-4 space-y-1 text-sm text-gray-600">
        <div><span class="font-semibold text-gray-700">Action:</span> {{ str_replace('_', ' ', $record->action) }}</div>
        <div><span class="font-semibold text-gray-700">Model:</span> {{ $record->model_type ? class_basename($record->model_type) : 'System' }}</div>
        @if ($record->model_id)
            <div><span class="font-semibold text-gray-700">Record ID:</span> {{ $record->model_id }}</div>
        @endif
    </div>
@endif

@if (empty($changes))
    <div class="text-sm text-gray-500">
        No non-sensitive changes captured. This can happen when only redacted fields (like passwords or PII) were updated.
    </div>
@else
    <div class="space-y-3 text-sm">
        @foreach ($changes as $field => $value)
            <div class="rounded border border-gray-200 p-3">
                <div class="font-semibold text-gray-700">{{ $field }}</div>

                @if (is_array($value) && array_key_exists('from', $value) && array_key_exists('to', $value))
                    <div class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-2">
                        <div>
                            <div class="text-xs uppercase text-gray-400">From</div>
                            <div class="whitespace-pre-wrap break-words">{{ $formatValue($value['from']) }}</div>
                        </div>
                        <div>
                            <div class="text-xs uppercase text-gray-400">To</div>
                            <div class="whitespace-pre-wrap break-words">{{ $formatValue($value['to']) }}</div>
                        </div>
                    </div>
                @else
                    <div class="mt-2 whitespace-pre-wrap break-words">{{ $formatValue($value) }}</div>
                @endif
            </div>
        @endforeach
    </div>
@endif
