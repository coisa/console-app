<?php
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package Console\Container\Factory
 */

declare(strict_types=1);

namespace Console\Container\Factory;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

/**
 * Class ContainerCommandLoaderFactory
 *
 * @package Console\Container\Factory
 */
final class ContainerCommandLoaderFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ContainerCommandLoader
     */
    public function __invoke(ContainerInterface $container): ContainerCommandLoader
    {
        $config = $container->has('config') ? $container->get('config') : [];

        return new ContainerCommandLoader($container, $config['routes'] ?? []);
    }
}
