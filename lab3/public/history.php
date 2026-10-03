<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use Lab3\Sport\Arena;
use Lab3\Support\View;

View::render('history', [
    'title'   => 'История матчей',
    'section' => 'sport',
    'page'    => 'history',
    'styles'  => ['sport'],
    'history' => Arena::history(),
]);
