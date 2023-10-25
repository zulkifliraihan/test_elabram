<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPERADMIN = 'superadmin';
    case MEMBER = 'member';

    public static function renderLabel($value)
    {
        return match ($value) {
            self::SUPERADMIN => 'Superadmin',
            self::MEMBER => 'Member',
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

    public static function renderOptionsForSwitchingMemberRole(): array
    {
        return collect(self::cases())
            ->map(function ($item) {
                return [
                    'label' => (string) str($item->value)->title(),
                    'value' => $item->value,
                ];
            })
            ->values()
            ->toArray();
    }
}
