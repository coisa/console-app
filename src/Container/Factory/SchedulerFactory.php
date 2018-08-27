<?php
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package Console\Container\Factory
 */

declare(strict_types=1);

namespace Console\Container\Factory;

use Cron\CronExpression;
use GO\Scheduler;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class SchedulerFactory
 *
 * @package Console\Container\Factory
 */
final class SchedulerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return Scheduler
     */
    public function __invoke(ContainerInterface $container): Scheduler
    {
        $scheduler = new Scheduler();
        $this->configure($container, $scheduler);

        return $scheduler;
    }

    /**
     * @param ContainerInterface $container
     * @param Scheduler $application
     *
     * @return Scheduler
     */
    private function configure(ContainerInterface $container, Scheduler $scheduler): Scheduler
    {
        $config = $container->has('config') ?
            $container->get('config') : [];

        if (!array_key_exists(Scheduler::class, $config)) {
            return $scheduler;
        }

        $logger = $container->has(LoggerInterface::class) ?
            $container->get(LoggerInterface::class) : new NullLogger();

        foreach ($config[Scheduler::class] as $interval => $jobs) {
            foreach ($jobs as $id => $script) {
                $this->configureJob(
                    $scheduler,
                    $script,
                    $interval,
                    $logger,
                    !is_numeric($id) ? $id : null
                );
            }
        }

        return $scheduler;
    }

    /**
     * @param Scheduler $scheduler
     * @param string $script
     * @param string $interval
     * @param LoggerInterface $logger
     * @param string|null $id
     */
    private function configureJob(
        Scheduler $scheduler,
        string $script,
        string $interval,
        LoggerInterface $logger,
        string $id = null
    ): void {
        $job = $scheduler->raw($script, [], $id);
        $job->at($interval);

        $job->before(function () use ($logger, $script) {
            $logger->info('Scheduled script started.', compact('script'));
        });

        $job->then(function ($output) use ($logger, $script) {
            $logger->info('Scheduled script finished.', compact('script'));
            $logger->debug('Scheduled script output.', compact('script', 'output'));
        });
    }
}
