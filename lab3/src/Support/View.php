<?php
declare(strict_types=1);

namespace Lab3\Support;

final class View
{
    private const TEMPLATES = __DIR__ . '/../../templates';

    /**
     * Рендерит шаблон страницы внутри общего layout.
     *
     * @param array<string, mixed> $data
     */
    public static function render(string $template, array $data = []): void
    {
        $content = self::capture(self::TEMPLATES . "/{$template}.php", $data);

        echo self::capture(self::TEMPLATES . '/layout.php', $data + ['content' => $content]);
    }

    private static function capture(string $file, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;

        return (string) ob_get_clean();
    }
}
