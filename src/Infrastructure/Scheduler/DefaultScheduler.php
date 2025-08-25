<?php

namespace App\Infrastructure\Scheduler;

use App\Domain\Budget\Scheduler\Message\CreateAnnuallyHistoryBudgetMessage;
use DateTimeImmutable;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule as SymfonySchedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsSchedule]
class DefaultScheduler implements ScheduleProviderInterface
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    public function getSchedule(): SymfonySchedule
    {
        return new SymfonySchedule()
            ->stateful($this->cache) // ensure missed tasks are executed
            ->processOnlyLastMissedRun(true) // ensure only last missed task is run
            ->with(
                RecurringMessage::every('P1Y', new CreateAnnuallyHistoryBudgetMessage(), new DateTimeImmutable('first day of january 01:00:00'))
            )

            // add your own tasks here
            // see https://symfony.com/doc/current/scheduler.html#attaching-recurring-messages-to-a-schedule
        ;
    }
}
