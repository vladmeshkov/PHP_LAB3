<?php
declare(strict_types=1);

// PSR-4: пространство имён Lab3\ отображается на каталог src/
spl_autoload_register(static function (string $class): void {
    $prefix = 'Lab3\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file = __DIR__ . '/' . str_replace('\\', '/', $relative) . '.php';

    if (is_file($file)) {
        require $file;
    }
});
