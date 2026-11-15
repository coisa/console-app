<?php

declare(strict_types=1);

namespace Console\Container;

use FastForward\Config\ConfigInterface;
use Interop\Container\ServiceProviderInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/** Adapts the sandbox's existing factory configuration to the Fast Forward container. */
final readonly class SandboxServiceProvider implements ServiceProviderInterface
{
    public function __construct(private ConfigInterface $config) {}

    public function getFactories(): array
    {
        $factories = [
            'config' => fn (): array => $this->config->toArray(),
            Logger::class => static fn (): Logger => new Logger('console', [new StreamHandler('php://stderr')]),
            LoggerInterface::class => static fn (ContainerInterface $c): LoggerInterface => $c->get(Logger::class),
            'logger' => static fn (ContainerInterface $c): LoggerInterface => $c->get(LoggerInterface::class),
            'monolog' => static fn (ContainerInterface $c): LoggerInterface => $c->get(LoggerInterface::class),
        ];
        $dependencies = $this->config->get('dependencies');
        foreach ($dependencies['invokables'] ?? [] as $id => $class) {
            $factories[$id] = static fn () => new $class();
        }
        foreach ($dependencies['factories'] ?? [] as $id => $factory) {
            $factories[$id] = static fn (ContainerInterface $c) => (new $factory())($c);
        }
        foreach ($dependencies['aliases'] ?? [] as $id => $target) {
            $factories[$id] = static fn (ContainerInterface $c) => $c->get($target);
        }
        return $factories;
    }

    public function getExtensions(): array
    {
        return [];
    }
}
