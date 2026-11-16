<?php
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package Console\Container\Factory
 */

declare(strict_types=1);

namespace Console\Container\Factory;

use FastForward\Config\ConfigInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Helper\HelperSet;

/**
 * Class PdoFactory
 *
 * @package Console\Container\Factory
 */
final class PdoFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return \PDO
     */
    public function __invoke(ContainerInterface $container): \PDO
    {
        $config = $container->get(ConfigInterface::class)->toArray();
        $pdoConfig = $config[\PDO::class];

        return new \PDO(
            $pdoConfig['dsn'],
            $pdoConfig['username'] === false ? null : $pdoConfig['username'],
            $pdoConfig['passwd'] === false ? null : $pdoConfig['passwd']
        );
    }
}
