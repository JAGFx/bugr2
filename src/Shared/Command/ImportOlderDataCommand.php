<?php

namespace App\Shared\Command;

use App\Domain\Account\Entity\Account;
use App\Domain\Budget\Entity\Budget;
use App\Shared\Utils\YearRange;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('bugr:old-data:import')]
class ImportOlderDataCommand
{
    public const string DEFAULT_ACCOUNT = 'Livret A';
    private Connection $connection;
    private Connection $oldBugrManager;

    public function __construct(
        private readonly string $exportPath,
        private readonly EntityManagerInterface $entityManager,
        private readonly ManagerRegistry $managerRegistry,
    ) {
        $this->connection = $this->entityManager->getConnection();

        /** @var Connection $oldBugrManager */
        $oldBugrManager       = $this->managerRegistry->getConnection('old_bugr');
        $this->oldBugrManager = $oldBugrManager;
    }

    public function __invoke(OutputInterface $output): int
    {
        $this->createNewThings();
        $this->migrateBudget();
        // 02_bugr_periodic_entry.csv
        //        $this->parsePeriodicEntry();
        // 03_bugr_periodic_entry_budget.csv
        //        $this->parsePeriodicEntryBudget();
        // 04_entry.csv
        //        $this->parseEntry();
        //        // 06_budget_balance_by_year.csv
        //        $this->applyBudgetBalance();
        //        // 07_entries_forecast.csv
        //        $this->removeForecastEntries();
        //        // 08_entries_forecast_balance_by_year.csv
        //        $this->adjustForecastEntriesBalance();
        //        // 05_entry_missing_budget_csv.csv
        //        $this->correctEntriesWithMissingBudget();
        //
        //        $this->applyForecastForCurrentYear();

        return Command::SUCCESS;
    }

    public function createNewThings(): void
    {
        $accounts = [self::DEFAULT_ACCOUNT, 'LDDS'];

        foreach ($accounts as $accountName) {
            $account = new Account()
                ->setName($accountName)
                ->setEnable(true);

            $this->entityManager->persist($account);
        }

        $this->entityManager->flush();
    }

    private function migrateBudget(): void
    {
        $defaultAccount = $this->fetchDefaultAccount();

        if (is_null($defaultAccount)) {
            return;
        }

        $result = $this->oldBugrManager->executeQuery('SELECT b.*
            FROM budget b;');

        foreach ($result->fetchAllAssociative() as $oldBudget) {
            $budget = new Budget()
                ->setName($oldBudget['name'])
                ->setAmount($oldBudget['amount'])
                ->setEnable($oldBudget['enable'])
            ;

            $budget->setCreatedAt(new DateTimeImmutable($oldBudget['created_at'] ?? ''));
            $budget->setUpdatedAt(new DateTimeImmutable($oldBudget['updated_at'] ?? ''));

            $this->entityManager->persist($budget);
        }

        $this->entityManager->flush();
    }

    public function applyForecastForCurrentYear(): void
    {
        $forecasts = $this->connection->fetchAllAssociative(
            'SELECT b.id, b.name, ROUND(b.amount / 12, 3) as amount
                    FROM budget b
                    WHERE b.enable'
        );

        $currentMonthNumber = (int) new DateTimeImmutable()->format('m');
        for ($monthNumber = 1; $monthNumber <= $currentMonthNumber; ++$monthNumber) {
            foreach ($forecasts as $forecast) {
                $firstDayOfMont = new DateTimeImmutable()
                    ->setDate(YearRange::current(), $monthNumber, 1)
                    ->setTime(0, 0);

                /** @var string $name */
                $name = $forecast['name'];

                $this->connection->insert('entry', [
                    'budget_id' => $forecast['id'],
                    'amount'    => $forecast['amount'],
                    'name'      => sprintf(
                        'Provision of %s - %s',
                        $name,
                        $firstDayOfMont->format('Y M'),
                    ),
                    'created_at' => $firstDayOfMont->format('c'),
                    'updated_at' => new DateTimeImmutable()->format('c'),
                ]);
            }
        }

        $this->connection->insert('entry', [
            'budget_id'  => 1275,
            'amount'     => -928.05,
            'name'       => 'Regularisation',
            'created_at' => new DateTimeImmutable()->format('c'),
            'updated_at' => new DateTimeImmutable()->format('c'),
        ]);
    }

    public function fetchDefaultAccount(): ?Account
    {
        /** @var Account $account */
        $account = $this->entityManager
            ->createQueryBuilder()
            ->from(Account::class, 'a')
            ->select('a')
            ->andWhere('a.name = :name')
            ->setParameter('name', self::DEFAULT_ACCOUNT)
            ->getQuery()
            ->getOneOrNullResult();

        return $account;
    }
}
