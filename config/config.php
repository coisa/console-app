<?php

declare(strict_types=1);

use Console\Container\ConfigProvider;
use function FastForward\Config\config;

$settings = require __DIR__ . '/settings.php';
$sources = [$settings, ConfigProvider::class];
foreach (['global.php', '*.global.php', 'local.php', '*.local.php'] as $pattern) {
    foreach (glob(__DIR__ . '/autoload/' . $pattern) ?: [] as $file) {
        $sources[] = $file;
    }
}
// Rebuild configuration on each bootstrap; no stale Zend-generated cache is reused.
return config(...$sources);
