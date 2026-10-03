<?php
use Lab3\Support\Flash;
use Lab3\Support\Theme;

$theme = Theme::current();
$sections = [
    'shop'  => [
        'label' => 'Магазин',
        'pages' => [
            'shop'    => ['shop.php', 'Каталог'],
            'company' => ['company.php', 'О компании'],
            'journal' => ['journal.php', 'Журнал'],
        ],
    ],
    'sport' => [
        'label' => 'Спорт',
        'pages' => [
            'sport'   => ['sport.php', 'Матчи'],
            'history' => ['history.php', 'История матчей'],
        ],
    ],
];
$nav = $section ? $sections[$section]['pages'] : [];
?>
<!DOCTYPE html>
<html lang="ru" data-theme="<?= e($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> · ООП на PHP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap&subset=cyrillic">
    <link rel="stylesheet" href="assets/css/base.css">
<?php foreach ($styles as $style): ?>
    <link rel="stylesheet" href="assets/css/<?= e($style) ?>.css">
<?php endforeach; ?>
</head>
<body>
<header class="topbar">
    <div class="container topbar__inner">
        <a class="brand" href="index.php">ООП на PHP</a>
<?php if ($section): ?>
        <span class="topbar__section"><?= e($sections[$section]['label']) ?></span>
<?php endif; ?>
        <nav class="nav" aria-label="Разделы">
<?php foreach ($nav as $key => [$href, $label]): ?>
            <a href="<?= e($href) ?>"<?= $key === $page ? ' aria-current="page"' : '' ?>><?= e($label) ?></a>
<?php endforeach; ?>
        </nav>
        <form method="post" class="theme-switch" aria-label="Тема оформления">
            <input type="hidden" name="action" value="set_theme">
            <button name="theme" value="light"<?= $theme === Theme::LIGHT ? ' class="is-active" aria-pressed="true"' : ' aria-pressed="false"' ?>>Светлая</button>
            <button name="theme" value="dark"<?= $theme === Theme::DARK ? ' class="is-active" aria-pressed="true"' : ' aria-pressed="false"' ?>>Тёмная</button>
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
        <form method="post">
            <button class="btn btn--link" name="action" value="reset_state">Сбросить данные</button>
        </form>
    </div>
</footer>
<script src="assets/js/forms.js" defer></script>
</body>
</html>
