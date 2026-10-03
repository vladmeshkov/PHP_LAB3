<?php
declare(strict_types=1);

namespace Lab3\Support;

final class Flash
{
    public static function add(string $type, string $text): void
    {
        $_SESSION['flash'][] = ['type' => $type, 'text' => $text];
    }

    public static function pull(): array
    {
        $messages = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);

        return $messages;
    }
}
