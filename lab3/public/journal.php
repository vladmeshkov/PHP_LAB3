<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use Lab3\Shop\Store;
use Lab3\Support\View;

View::render('journal', [
    'title'   => 'Журнал',
    'section' => 'shop',
    'page'    => 'journal',
    'styles'  => ['shop'],
    'journal' => Store::journal(),
    'orders'  => Store::orders(),
]);
