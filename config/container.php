<?php

declare(strict_types=1);

use function FastForward\Container\container;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$config = require __DIR__ . '/config.php';
$providers = require __DIR__ . '/providers.php';

return container($config, ...$providers);
