<?php

declare(strict_types=1);

namespace Console\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;

/** Logs a sandbox invocation without coupling the handler to Symfony's base command. */
#[AsCommand(name: 'console:index', description: 'Log a sandbox greeting.')]
final readonly class IndexCommand
{
    /** @param LoggerInterface $logger Application logger injected by the container. */
    public function __construct(private LoggerInterface $logger) {}

    /**
     * @param string|null $text Optional text retained in the log context.
     * @param bool $yell Whether to uppercase the greeting.
     * @return int Successful process exit code.
     */
    public function __invoke(
        #[Argument(description: 'Text to include in the log context.')]
        ?string $text = null,

        #[Option(description: 'Uppercase the greeting.', shortcut: 'y')]
        bool $yell = false,
    ): int {
        $this->logger->info($yell ? 'HELLO WORLD!' : 'Hello World!', [
            'argv' => ['text' => $text],
            'options' => ['yell' => $yell],
        ]);

        return Command::SUCCESS;
    }
}
