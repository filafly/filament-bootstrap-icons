<?php

namespace Filafly\Icons\Bootstrap\Enums;

use Filafly\Icons\Contracts\StyleEnum;

enum BootstrapStyle: string implements StyleEnum
{
    case Regular = '';
    case Fill = '-fill';

    public function getStyleName(): string
    {
        return match ($this) {
            self::Regular => 'regular',
            self::Fill => 'fill',
        };
    }

    public function getEnumSuffix(): string
    {
        return match ($this) {
            self::Regular => 'Regular',
            self::Fill => 'Fill',
        };
    }

    public static function getStyleNames(): array
    {
        return ['regular', 'fill'];
    }

    public static function fromStyleName(string $styleName): ?self
    {
        return match (strtolower($styleName)) {
            'regular' => self::Regular,
            'fill' => self::Fill,
            default => null,
        };
    }
}
