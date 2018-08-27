<?php
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package Console\Container\Factory
 */

declare(strict_types=1);

use CoiSA\Monolog\ConfigProvider as LoggerConfigProvider;
use Console\Container\ConfigProvider;
use Zend\ConfigAggregator\ArrayProvider;
use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\PhpFileProvider;

$settings           = require __DIR__ . '/settings.php';

$aggregator = new ConfigAggregator(
    [
        new ArrayProvider($settings),
        new PhpFileProvider('config/autoload/{{,*.}global,{,*.}local}.php'),
        ConfigProvider::class,
        LoggerConfigProvider::class,
    ],
    $settings['config_cache_path']
);

return $aggregator->getMergedConfig();
