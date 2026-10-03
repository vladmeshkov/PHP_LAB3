<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use Lab3\Support\View;

View::render('home', [
    'title'   => 'Главное меню',
    'section' => null,
    'page'    => 'home',
    'styles'  => ['home'],
]);
