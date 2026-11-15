<?php

declare(strict_types=1);

use Console\Container\SandboxServiceProvider;
use function FastForward\Container\container;

require_once dirname(__DIR__) . '/vendor/autoload.php';
$config = require __DIR__ . '/config.php';

return container(new SandboxServiceProvider($config), $config);
