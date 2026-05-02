<?php

namespace App\Enums;

enum AcademicYear: string
{
    case AY2022_2023 = '2022-2023';
    case AY2023_2024 = '2023-2024';
    case AY2024_2025 = '2024-2025';
    case AY2025_2026 = '2025-2026';
    case AY2026_2027 = '2026-2027';
    case AY2027_2028 = '2027-2028';
    case AY2028_2029 = '2028-2029';
    case AY2029_2030 = '2029-2030';
    case AY2030_2031 = '2030-2031';
    case AY2031_2032 = '2031-2032';

    public static function values(): array
    {
        return array_map(
            fn (self $year): string => $year->value,
            self::cases(),
        );
    }

    public static function options(): array
    {
        return array_combine(self::values(), self::values());
    }

    public static function current(): self
    {
        $startYear = now()->month >= 6 ? now()->year : now()->year - 1;

        return self::from(sprintf('%d-%d', $startYear, $startYear + 1));
    }
}
