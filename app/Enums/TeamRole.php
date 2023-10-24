<?php

namespace App\Enums;

enum TeamRole: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case MEMBER = 'member';

    public static function renderLabel($value)
    {
        return match ($value) {
            self::OWNER => 'Owner',
            self::ADMIN => 'Admin',
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
