<?php
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package Console\CommandLoader
 */

declare(strict_types=1);

namespace Console\CommandLoader;

use Psr\Container\ContainerInterface;
use Silly\Command\ExpressionParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;

/**
 * Class ExpressionParserCommandLoader
 *
 * @package Console\CommandLoader
 */
final class ExpressionParserCommandLoader implements CommandLoaderInterface
{
    /** @var ContainerInterface */
    private $container;

    /** @var array */
    private $commandMap;

    /**
     * SillyComandLoader constructor.
     *
     * @param ContainerInterface $container
     * @param ExpressionParser $parser
     * @param array $commandMap
     */
    public function __construct(ContainerInterface $container, ExpressionParser $parser, array $commandMap)
    {
        $this->container = $container;

        foreach ($commandMap as $expr => $command) {
            $result = $parser->parse($expr);
            $result['command'] = $command;

            $this->commandMap[$result['name']] = $result;
        }
    }

    /**
     * @return string[]
     */
    public function getNames()
    {
        return array_keys($this->commandMap);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name): bool
    {
        return array_key_exists($name, $this->commandMap) &&
            $this->container->has($this->commandMap[$name]['command']);
    }

    /**
     * @param string $name
     *
     * @return Command
     */
    public function get($name): Command
    {
        if (!$this->has($name)) {
            throw new CommandNotFoundException(sprintf('Command "%s" does not exist.', $name));
        }

        $command = $this->container->get($this->commandMap[$name]['command']);

        $command->setName($name);
        $command->getDefinition()->addArguments($this->commandMap[$name]['arguments']);
        $command->getDefinition()->addOptions($this->commandMap[$name]['options']);

        return $command;
    }
}
