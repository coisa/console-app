<?php
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package Console\Container\Factory
 */

declare(strict_types=1);

namespace Console\Container\Factory;

use Console\CommandLoader\ExpressionParserCommandLoader;
use Psr\Container\ContainerInterface;
use Silly\Command\ExpressionParser;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;

/**
 * Class CommandLoaderFactory
 *
 * @package Console\Container\Factory
 */
final class CommandLoaderFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return CommandLoaderInterface
     */
    public function __invoke(ContainerInterface $container): CommandLoaderInterface
    {
        $config = $container->has('config') ? $container->get('config') : [];
        $routes = $config[CommandLoaderInterface::class] ?? [];

        return new ExpressionParserCommandLoader(
            $container,
            $container->get(ExpressionParser::class),
            $routes
        );
    }
}
