<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use Lab3\Support\View;

$config = require __DIR__ . '/../src/config.php';

View::render('company', [
    'title'   => 'О компании',
    'section' => 'shop',
    'page'    => 'company',
    'styles'  => ['company'],
    'company' => $config['company'],
]);
