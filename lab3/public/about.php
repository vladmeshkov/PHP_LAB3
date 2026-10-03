<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use Lab3\Support\View;

View::render('about', [
    'title'   => 'О проекте',
    'page'    => 'about',
    'styles'  => ['about'],
    'scripts' => [],
    'author'  => (require __DIR__ . '/../src/config.php')['author'],
]);
