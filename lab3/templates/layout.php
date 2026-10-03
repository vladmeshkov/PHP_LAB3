<?php
use Lab3\Support\Flash;
use Lab3\Support\Theme;

$theme = Theme::current();
$nav = [
    'shop'  => ['index.php', 'Магазин'],
    'sport' => ['sport.php', 'Спорт'],
    'about' => ['about.php', 'О проекте'],
];
?>
<!DOCTYPE html>
<html lang="ru" data-theme="<?= e($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> · Лабораторная работа №3</title>
    <link rel="stylesheet" href="assets/css/base.css">
<?php foreach ($styles as $style): ?>
    <link rel="stylesheet" href="assets/css/<?= e($style) ?>.css">
<?php endforeach; ?>
</head>
<body>
<header class="topbar">
    <div class="container topbar__inner">
        <a class="brand" href="index.php">ООП на PHP<span class="brand__tag">лаб. 3</span></a>
        <nav class="nav" aria-label="Разделы">
<?php foreach ($nav as $key => [$href, $label]): ?>
            <a href="<?= e($href) ?>"<?= $key === $page ? ' aria-current="page"' : '' ?>><?= e($label) ?></a>
<?php endforeach; ?>
        </nav>
        <form method="post" class="topbar__theme">
            <button class="btn btn--ghost" name="action" value="toggle_theme">
                <?= $theme === Theme::DARK ? 'Светлая тема' : 'Тёмная тема' ?>
            </button>
        </form>
    </div>
</header>

<main class="container">
<?php foreach (Flash::pull() as $message): ?>
    <div class="notice notice--<?= e($message['type']) ?>" role="status"><?= e($message['text']) ?></div>
<?php endforeach; ?>
<?= $content ?>
</main>

<footer class="footer">
    <div class="container footer__inner">
        <span>Высокоуровневые языки программирования · вариант 5</span>
        <form method="post">
            <button class="btn btn--link" name="action" value="reset_state">Сбросить демо-данные</button>
        </form>
    </div>
</footer>
<?php foreach ($scripts as $script): ?>
<script src="assets/js/<?= e($script) ?>.js" defer></script>
<?php endforeach; ?>
</body>
</html>
