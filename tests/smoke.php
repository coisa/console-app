<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use GO\Scheduler;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

chdir(dirname(__DIR__));
putenv('ENABLE_CACHE=0');
$container = require 'config/container.php';
$logger = $container->get(LoggerInterface::class);
if (!$logger instanceof LoggerInterface) {
    throw new RuntimeException('Logger service is unavailable.');
}
$application = $container->get(Application::class);
$application->setAutoExit(false);
$tester = new ApplicationTester($application);
foreach ([['command' => 'list'], ['command' => 'console:index', 'text' => 'PHP 8.3 smoke test']] as $input) {
    if ($tester->run($input) !== 0) {
        throw new RuntimeException('Console failed: ' . $tester->getDisplay());
    }
}
// Construct configured jobs without running scheduled commands or connecting to a database.
if (!$container->get(Scheduler::class) instanceof Scheduler) {
    throw new RuntimeException('Scheduler service is unavailable.');
}
fwrite(STDOUT, "Container, logger, console and scheduler smoke tests passed.\n");
