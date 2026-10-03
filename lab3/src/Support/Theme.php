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

    public static function set(string $theme): void
    {
        $_SESSION['theme'] = $theme === self::DARK ? self::DARK : self::LIGHT;
    }

    public static function init(): void
    {
        $_SESSION['theme'] ??= self::LIGHT;
    }
}
