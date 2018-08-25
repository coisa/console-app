<?php
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package Console\Container\Factory
 */

declare(strict_types=1);

namespace Console\Container\Factory;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ApplicationFactory
 *
 * @package Console\Container\Factory
 */
final class ApplicationFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return Application
     */
    public function __invoke(ContainerInterface $container): Application
    {
        $application = new Application();

        if ($container->has(CommandLoaderInterface::class)) {
            $commandLoader = $container->get(CommandLoaderInterface::class);
            $application->setCommandLoader($commandLoader);
        }

        if ($container->has(EventDispatcherInterface::class)) {
            $dispatcher = $container->get(EventDispatcherInterface::class);
            $application->setDispatcher($dispatcher);
        }

        if ($container->has(HelperSet::class)) {
            $helperSet = $container->get(HelperSet::class);
            $application->setHelperSet($helperSet);
        }

        return $application;
    }
}
