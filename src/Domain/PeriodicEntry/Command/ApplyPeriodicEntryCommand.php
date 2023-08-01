<?php

namespace App\Domain\PeriodicEntry\Command;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand('bugr:periodic-entry:apply')]
class ApplyPeriodicEntryCommand extends Command
{
    //    public function __construct(
    //        private readonly LoggerInterface $logger
    //    ) {
    //        parent::__construct();
    //    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        try {
            dump('Plop');
            throw new Exception();
        } catch (Throwable $throwable) {
            $symfonyStyle->error($throwable->getMessage());
            //            $this->logger->error('Periodic entry command: Unable to execute cron job', [
            //                'exceptionMessage' => $exception->getMessage(),
            //                'exceptionClass'   => $exception::class,
            //            ]);

            return Command::FAILURE;
        }

//        return Command::SUCCESS;
    }
}
