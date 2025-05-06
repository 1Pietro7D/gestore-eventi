<?php

spl_autoload_register(function ($class) {
    $prefix = '';
    $baseDir = __DIR__ . '/../';

    $class = ltrim($class, '\\');
    $file = $baseDir . str_replace(['\\', '_'], '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
