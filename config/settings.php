<?php

declare(strict_types=1);

use GO\Scheduler;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Zend\ConfigAggregator\ConfigAggregator;

return [
    # debug settings
    'debug'                        => getenv('DEBUG') ?: false,

    # config cache settings
    'config_cache_path'            => 'data/config-cache.php',
    ConfigAggregator::ENABLE_CACHE => getenv('ENABLE_CACHE') ?: false,

    # routes settings
    CommandLoaderInterface::class  => require __DIR__ . '/routes.php',

    # schedules settings
    Scheduler::class               => require __DIR__ . '/schedules.php',

    # pdo settings
    \PDO::class                    => [
        'dsn'      => getenv('PDO_DSN') ?: 'sqlite::memory:',
        'username' => getenv('PDO_USERNAME'),
        'passwd'   => getenv('PDO_PASSWD')
    ]
];
