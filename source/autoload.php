<?php
spl_autoload_register(function (string $className) {
    $fileName = [
        __DIR__. '/' . str_replace('\\', '/', $className . '.php'),
        //__DIR__ . $className . '.php'
    ];

    foreach ($fileName as $file) {
        if (file_exists($file)) {
            require_once $file;
            break;
        }
    }
});