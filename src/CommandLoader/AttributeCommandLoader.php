<?php

declare(strict_types=1);

namespace Console\CommandLoader;

use LogicException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LazyCommand;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;

/** Discovers command metadata without constructing handlers or their dependencies. */
final class AttributeCommandLoader implements CommandLoaderInterface
{
    /** @var array<string, array{service: class-string, name: string, aliases: list<string>, description: string, hidden: bool}> */
    private array $definitions = [];

    /** @var array<string, Command> Cached lazy wrappers keyed by canonical name. */
    private array $commands = [];

    /**
     * @param ContainerInterface $container Container consulted only for the selected command.
     * @param list<class-string> $services Invokable command service identifiers.
     */
    public function __construct(private readonly ContainerInterface $container, array $services)
    {
        foreach ($services as $service) {
            $attributes = (new ReflectionClass($service))->getAttributes(AsCommand::class);

            if (count($attributes) !== 1) {
                throw new LogicException('Command must declare one AsCommand attribute: ' . $service);
            }

            $metadata = $attributes[0]->newInstance();

            $names = explode('|', $metadata->name);
            $hidden = '' === $names[0];

            if ($hidden) {
                array_shift($names);
            }

            $name = array_shift($names);
            $definition = [
                'service' => $service,
                'name' => $name,
                'aliases' => $names,
                'description' => $metadata->description ?? '',
                'hidden' => $hidden,
            ];

            foreach ([$name, ...$names] as $publicName) {
                if (!is_string($publicName) || '' === $publicName || isset($this->definitions[$publicName])) {
                    throw new LogicException('Empty or duplicate command name: ' . $publicName);
                }

                $this->definitions[$publicName] = $definition;
            }
        }
    }

    /** @return list<string> Public names discovered solely from attributes. */
    public function getNames(): array
    {
        return array_keys($this->definitions);
    }

    /** @param string $name Public command name. @return bool Whether it is registered. */
    public function has(string $name): bool
    {
        return isset($this->definitions[$name]);
    }

    /**
     * @param string $name Public command name.
     * @return Command Cached wrapper, with handler construction deferred until execution.
     * @throws CommandNotFoundException When the name is not registered.
     */
    public function get(string $name): Command
    {
        if (!$this->has($name)) {
            throw new CommandNotFoundException(sprintf('Command "%s" does not exist.', $name));
        }

        $definition = $this->definitions[$name];
        $container = $this->container;
        $canonicalName = $definition['name'];

        return $this->commands[$canonicalName] ??= new LazyCommand(
            $canonicalName,
            $definition['aliases'],
            $definition['description'],
            $definition['hidden'],
            static function () use ($container, $definition): Command {
                $handler = $container->get($definition['service']);

                if (!is_callable($handler)) {
                    throw new LogicException('Command service must be invokable: ' . $definition['service']);
                }

                return new Command(null, $handler);
            },
        );
    }
}
