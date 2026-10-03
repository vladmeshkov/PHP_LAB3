<?php
declare(strict_types=1);

namespace Lab3\Support;

/**
 * Хранилище состояния демо-приложения внутри $_SESSION.
 * Всё лежит под одним ключом, чтобы сброс не затрагивал тему оформления.
 */
final class State
{
    private const ROOT = 'lab3';

    /** Увеличивайте при изменении свойств классов, лежащих в сессии: старое состояние будет сброшено. */
    private const VERSION = 4;

    public static function boot(): void
    {
        if (($_SESSION[self::ROOT . '_version'] ?? null) !== self::VERSION) {
            unset($_SESSION[self::ROOT]);
            $_SESSION[self::ROOT . '_version'] = self::VERSION;
        }
    }

    public static function remember(string $key, callable $factory): mixed
    {
        if (!isset($_SESSION[self::ROOT][$key])) {
            $_SESSION[self::ROOT][$key] = $factory();
        }

        return $_SESSION[self::ROOT][$key];
    }

    public static function put(string $key, mixed $value): void
    {
        $_SESSION[self::ROOT][$key] = $value;
    }

    public static function reset(): void
    {
        unset($_SESSION[self::ROOT]);
    }
}
