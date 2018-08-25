<?php

declare(strict_types=1);

use Zend\ConfigAggregator\ConfigAggregator;

return [
    # debug settings
    'debug'                        => getenv('DEBUG') ?? false,

    # config cache settings
    'config_cache_path'            => 'data/config-cache.php',
    ConfigAggregator::ENABLE_CACHE => getenv('ENABLE_CACHE') ?? true,

    # pdo settings
    \PDO::class    => [
        'dsn'      => getenv('PDO_DSN'),
        'username' => getenv('PDO_USERNAME'),
        'passwd'   => getenv('PDO_PASSWD')
    ]
];
