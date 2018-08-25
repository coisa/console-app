<?php
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package Console\Command
 */

declare(strict_types=1);

namespace Console\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class IndexCommand
 *
 * @package Console\Command
 */
final class IndexCommand extends Command
{
    /** @var \PDO */
    private $pdo;

    /** @var LoggerInterface */
    private $logger;

    /**
     * IndexCommand constructor.
     *
     * @param LoggerInterface $logger
     * @param \PDO $pdo
     */
    public function __construct(LoggerInterface $logger, \PDO $pdo)
    {
        $this->logger = $logger;
        $this->pdo = $pdo;

        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info('Hello World!');

        throw new \RuntimeException('Bye!');
    }
}
