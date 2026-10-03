<?php
declare(strict_types=1);

require_once __DIR__ . '/autoload.php';

use Lab3\Support\Flash;
use Lab3\Support\State;
use Lab3\Support\Theme;

// Классы должны быть доступны до session_start(), иначе объекты
// из сессии десериализуются как __PHP_Incomplete_Class.
session_start();
Theme::init();

function e(string|int|float|null $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(?string $to = null): void
{
    $to ??= $_SERVER['REQUEST_URI'] ?? '/';
    header('Location: ' . $to);
    exit;
}

// Действия, общие для всех страниц: смена темы и сброс данных.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action'] ?? '') {
        case 'set_theme':
            Theme::set((string) ($_POST['theme'] ?? ''));
            redirect();

        case 'reset_state':
            State::reset();
            Flash::add('info', 'Демо-данные сброшены к исходным значениям.');
            redirect();
    }
}
