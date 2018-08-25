<?php
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package Console\Container\Factory
 */

declare(strict_types=1);

namespace Console\Container\Factory;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\EventListener\ErrorListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class EventDispatcherFactory
 *
 * @package Console\Container\Factory
 */
final class EventDispatcherFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return EventDispatcherInterface
     */
    public function __invoke(ContainerInterface $container): EventDispatcherInterface
    {
        $dispatcher = new EventDispatcher();

        if ($container->has(ErrorListener::class)) {
            $subscriber = $container->get(ErrorListener::class);
            $dispatcher->addSubscriber($subscriber);
        }

        return $dispatcher;
    }
}
