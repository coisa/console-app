<?php

declare(strict_types=1);

namespace Console\Container;

use Console\Container\Factory\ApplicationFactory;
use Console\Container\Factory\CommandLoaderFactory;
use Console\Container\Factory\PdoFactory;
use Console\Container\Factory\SchedulerFactory;
use GO\Scheduler;
use Interop\Container\ServiceProviderInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;

/** Registers application services explicitly; configuration contains only values. */
final class SandboxServiceProvider implements ServiceProviderInterface
{
    /** @return array<string, callable> Lazy factories keyed by service identifier. */
    public function getFactories(): array
    {
        return [
            Application::class => new ApplicationFactory(),
            CommandLoaderInterface::class => new CommandLoaderFactory(),
            Scheduler::class => new SchedulerFactory(),
            \PDO::class => new PdoFactory(),
            Logger::class => static fn (): Logger => new Logger('console', [new StreamHandler('php://stderr')]),
            LoggerInterface::class => static fn (ContainerInterface $container): LoggerInterface => $container->get(Logger::class),
            'logger' => static fn (ContainerInterface $container): LoggerInterface => $container->get(LoggerInterface::class),
            'monolog' => static fn (ContainerInterface $container): LoggerInterface => $container->get(LoggerInterface::class),
        ];
    }

    /** @return array<string, callable> No implicit service mutations are required. */
    public function getExtensions(): array
    {
        return [];
    }
}
