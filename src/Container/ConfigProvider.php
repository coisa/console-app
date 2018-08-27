<?php
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package Console\Container
 */

declare(strict_types=1);

namespace Console\Container;

use Console\Container\Factory\ApplicationFactory;
use Console\Container\Factory\ContainerCommandLoaderFactory;
use Console\Container\Factory\EventDispatcherFactory;
use Console\Container\Initializer\CommandNameInitializer;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

/**
 * Class ConfigProvider
 *
 * @package Console\Container
 */
final class ConfigProvider
{
    /**
     * Provide application routes configurations
     *
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies()
        ];
    }

    /**
     * Returns application routes dependencies
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            'abstract_factories' => [
                ReflectionBasedAbstractFactory::class,
            ],
            'aliases'            => [
                CommandLoaderInterface::class   => ContainerCommandLoader::class,
                EventDispatcherInterface::class => EventDispatcher::class,
            ],
            'invokables'         => [
                Stopwatch::class => Stopwatch::class
            ],
            'factories'          => [
                Application::class            => ApplicationFactory::class,
                ContainerCommandLoader::class => ContainerCommandLoaderFactory::class,
                EventDispatcher::class        => EventDispatcherFactory::class,
            ],
        ];
    }
}
