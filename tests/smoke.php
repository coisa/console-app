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

// Attribute discovery and list metadata must not resolve a command service graph.
$unresolvedContainer = new class implements \Psr\Container\ContainerInterface {
    public function get(string $id): mixed
    {
        throw new RuntimeException('Command resolved during discovery: ' . $id);
    }

    public function has(string $id): bool
    {
        throw new RuntimeException('Container queried during discovery: ' . $id);
    }
};
$loader = new \Console\CommandLoader\AttributeCommandLoader($unresolvedContainer, [\Console\Command\IndexCommand::class]);
if ($loader->getNames() !== ['console:index'] || !$loader->has('console:index') || $loader->has('missing')) {
    throw new RuntimeException('Attribute command discovery failed.');
}
if ($loader->get('console:index') !== $loader->get('console:index')) {
    throw new RuntimeException('Lazy command wrapper was not cached.');
}
try {
    $loader->get('missing');
    throw new RuntimeException('Unknown command was accepted.');
} catch (\Symfony\Component\Console\Exception\CommandNotFoundException) {
}
$reflection = new ReflectionClass(\Console\Command\IndexCommand::class);
if ($reflection->isSubclassOf(\Symfony\Component\Console\Command\Command::class)) {
    throw new RuntimeException('The command handler must remain a POPO.');
}
if ($tester->run(['command' => 'console:index', '--yell' => true]) !== 0) {
    throw new RuntimeException('Attribute option binding failed.');
}
$dispatcher = $container->get(\Psr\EventDispatcher\EventDispatcherInterface::class);
$event = new stdClass();
if (!$dispatcher instanceof \FastForward\EventDispatcher\EventDispatcher || $dispatcher->dispatch($event) !== $event) {
    throw new RuntimeException('Fast Forward event dispatcher integration failed.');
}
if ($dispatcher !== $container->get(\Symfony\Contracts\EventDispatcher\EventDispatcherInterface::class)) {
    throw new RuntimeException('PSR and Symfony event dispatcher contracts must share the dispatcher.');
}
fwrite(STDOUT, "Attribute commands, lazy discovery and Fast Forward events passed.\n");

#[\Symfony\Component\Console\Attribute\AsCommand(name: 'hidden:fixture', aliases: ['fixture-alias'], hidden: true)]
final class HiddenCommandFixture
{
    public function __invoke(): int
    {
        return 0;
    }
}
$hiddenLoader = new \Console\CommandLoader\AttributeCommandLoader($unresolvedContainer, [HiddenCommandFixture::class]);
if ($hiddenLoader->getNames() !== ['hidden:fixture', 'fixture-alias'] || !$hiddenLoader->get('fixture-alias')->isHidden()) {
    throw new RuntimeException('Attribute aliases or hidden metadata were lost.');
}
if ($hiddenLoader->get('fixture-alias') !== $hiddenLoader->get('hidden:fixture')) {
    throw new RuntimeException('Aliases must share one lazy wrapper.');
}
if (!$container->get('config') instanceof \FastForward\Config\ConfigInterface) {
    throw new RuntimeException('The native config service contract must be preserved.');
}
