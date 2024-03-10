<?php

namespace App\Enums;

enum CourseStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [$case->value => ucfirst(strtolower($case->name))])->all();
    }
}
