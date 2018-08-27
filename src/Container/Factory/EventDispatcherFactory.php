<?php
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package Console\Container\Factory
 */

declare(strict_types=1);

namespace Console\Container\Factory;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\EventListener\ErrorListener;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Stopwatch\Stopwatch;

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
        $dispatcher = $this->configure($container, $dispatcher);

        return $dispatcher;
    }

    /**
     * @param ContainerInterface $container
     * @param EventDispatcherInterface $dispatcher
     *
     * @return EventDispatcherInterface
     */
    public function configure(
        ContainerInterface $container,
        EventDispatcherInterface $dispatcher
    ): EventDispatcherInterface {
        if ($container->has(ErrorListener::class)) {
            $subscriber = $container->get(ErrorListener::class);
            $dispatcher->addSubscriber($subscriber);
        }

        $canTraceEvents = class_exists(Stopwatch::class) &&
            $container->has(Stopwatch::class);

        if ($canTraceEvents) {
            $stopWatch = $container->get(Stopwatch::class);

            $logger = $container->has(LoggerInterface::class) ?
                $container->get(LoggerInterface::class) : null;

            $dispatcher = new TraceableEventDispatcher(
                $dispatcher,
                $stopWatch,
                $logger
            );
        }

        return $dispatcher;
    }
}
