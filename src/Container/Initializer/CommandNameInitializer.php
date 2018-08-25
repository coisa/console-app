<?php
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package Console\Container\Initializer
 */

declare(strict_types=1);

namespace Console\Container\Initializer;

use Interop\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Zend\ServiceManager\Initializer\InitializerInterface;

/**
 * Class CommandNameInitializer
 *
 * @package Console\Container\Initializer
 */
final class CommandNameInitializer implements InitializerInterface
{
    /**
     * @param ContainerInterface $container
     * @param object $instance
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $instance)
    {
        if (!$instance instanceof Command) {
            return;
        }

        if ($instance->getName()) {
            return;
        }

        $config = $container->get('config');

        $routes = $config['routes'];
        $commandNames = array_flip($routes);

        $className = get_class($instance);

        $instance->setName($commandNames[$className]);
    }
}
