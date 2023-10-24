<?php

namespace App\Enums;

enum StatusClaim: string
{
    case SUBMISSION = 'submission';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public static function renderLabel($value)
    {
        return match ($value) {
            self::SUBMISSION => 'Submission',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        };
    }

    public static function renderOptions(): array
    {
        return collect(self::cases())
            ->map(function ($item) {
                return [
                    'label' => (string) str($item->value)->title(),
                    'value' => $item->value,
                ];
            })
            ->prepend([
                'label' => 'All',
                'value' => null,
            ])
            ->values()
            ->toArray();
    }
}
