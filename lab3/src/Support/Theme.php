<?php
declare(strict_types=1);

namespace Lab3\Support;

final class Theme
{
    public const LIGHT = 'light';
    public const DARK  = 'dark';

    public static function current(): string
    {
        return $_SESSION['theme'] === self::DARK ? self::DARK : self::LIGHT;
    }

    public static function toggle(): void
    {
        $_SESSION['theme'] = self::current() === self::DARK ? self::LIGHT : self::DARK;
    }

    public static function init(): void
    {
        $_SESSION['theme'] ??= self::LIGHT;
    }
}
