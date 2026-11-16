<?php

declare(strict_types=1);

use GO\Scheduler;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;


return [
    # debug settings
    'debug'                        => getenv('DEBUG') ?: false,

    # config cache settings
    'config_cache_path'            => 'data/config-cache.php',
    'config_cache_enabled' => filter_var(getenv('ENABLE_CACHE') ?: false, FILTER_VALIDATE_BOOL),

    # command services; public names and inputs live in attributes
    CommandLoaderInterface::class  => require __DIR__ . '/commands.php',

    # schedules settings
    Scheduler::class               => require __DIR__ . '/schedules.php',

    # pdo settings
    \PDO::class                    => [
        'dsn'      => getenv('PDO_DSN') ?: 'sqlite::memory:',
        'username' => getenv('PDO_USERNAME'),
        'passwd'   => getenv('PDO_PASSWD')
    ]
];
