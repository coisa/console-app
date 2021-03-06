<?php
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package Console\Command
 */

declare(strict_types=1);

namespace Console\Command;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class IndexCommand
 *
 * @package Console\Command
 */
final class IndexCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info('Hello World!', [
            'argv' => $input->getArguments(),
            'options' => $input->getOptions()
        ]);
    }
}
