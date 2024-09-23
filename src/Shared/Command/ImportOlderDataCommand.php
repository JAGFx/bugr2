<?php

namespace App\Shared\Command;

use App\Infrastructure\LeagueCsv\CsvExtract;
use App\Shared\Utils\YearRange;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('bugr:old-data:import')]
class ImportOlderDataCommand extends Command
{
    private Connection $connection;

    public function __construct(
        private readonly string $exportPath,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();

        $this->connection = $this->entityManager->getConnection();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // 01_bugr_budget.csv
        $this->parseBudget();
        // 02_bugr_periodic_entry.csv
        $this->parsePeriodicEntry();
        // 03_bugr_periodic_entry_budget.csv
        $this->parsePeriodicEntryBudget();
        // 04_entry.csv
        $this->parseEntry();
        // 06_budget_balance_by_year.csv
        $this->applyBudgetBalance();
        // 07_entries_forecast.csv
        $this->removeForecastEntries();
        // 08_entries_forecast_balance_by_year.csv
        $this->adjustForecastEntriesBalance();
        // 05_entry_missing_budget_csv.csv
        $this->correctEntriesWithMissingBudget();

        $this->applyForecastForCurrentYear();

        return Command::SUCCESS;
    }

    private function records(string $fileName): array
    {
        $path = sprintf('%s/%s', $this->exportPath, $fileName);

        return CsvExtract::extractRecords($path);
    }

    /**
     * @file 01_bugr_budget.csv
     */
    private function parseBudget(): void
    {
        foreach ($this->records('01_bugr_budget.csv') as $record) {
            $this->connection->insert('budget', array_merge(
                $record,
                [
                    'updated_at' => empty($record['updated_at'])
                        ? (new DateTimeImmutable())->format('c')
                        : $record['updated_at'],
                ]
            ));
        }
    }

    /**
     * @file 02_bugr_periodic_entry.csv
     */
    private function parsePeriodicEntry(): void
    {
        foreach ($this->records('02_bugr_periodic_entry.csv') as $record) {
            $this->connection->insert('periodic_entry', $record);
        }
    }

    /**
     * @file 03_bugr_periodic_entry_budget.csv
     */
    private function parsePeriodicEntryBudget(): void
    {
        foreach ($this->records('03_bugr_periodic_entry_budget.csv') as $record) {
            $this->connection->insert('periodic_entry_budget', $record);
        }
    }

    /**
     * @file 04_entry.csv
     */
    private function parseEntry(): void
    {
        foreach ($this->records('04_entry.csv') as $record) {
            $this->connection->insert('entry', array_merge(
                $record,
                [
                    'budget_id' => empty($record['budget_id'])
                        ? null
                        : $record['budget_id'],
                    'updated_at' => (new DateTimeImmutable())->format('c'),
                ]
            ));
        }
    }

    /**
     * @file 05_entry_missing_budget_csv.csv
     */
    private function correctEntriesWithMissingBudget(): void
    {
        $entries = $this->records('05_entry_missing_budget_csv.csv');

        foreach ($entries as $entry) {
            $this->connection->update('entry', [
                'budget_id' => $entry['budget_id'],
            ], [
                'id' => $entry['id'],
            ]);
        }
    }

    /**
     * @file 06_budget_balance_by_year.csv
     */
    private function applyBudgetBalance(): void
    {
        $budgetsBalances = $this->records('06_budget_balance_by_year.csv');

        foreach ($budgetsBalances as $budgetBalance) {
            $this->connection->insert('entry', [
                'budget_id' => $budgetBalance['budget_id'],
                'amount'    => $budgetBalance['amount'],
                'name'      => sprintf(
                    'Provision of %s - %s',
                    $budgetBalance['name'],
                    $budgetBalance['year'],
                ),
                'created_at' => YearRange::firstDayOf($budgetBalance['year'])
                    ->setTime(rand(0, 24), rand(0, 60), rand(0, 60))
                    ->format('c'),
                'updated_at' => (new DateTimeImmutable())->format('c'),
            ]);
        }
    }

    /**
     * @file 07_entries_forecast.csv
     */
    private function removeForecastEntries(): void
    {
        $forecastEntries = $this->records('07_entries_forecast.csv');

        foreach ($forecastEntries as $forecastEntry) {
            $this->connection->delete('entry', [
                'id' => $forecastEntry['id'],
            ]);
        }
    }

    /**
     * @file 08_entries_forecast_balance_by_year.csv
     */
    private function adjustForecastEntriesBalance(): void
    {
        $forecastEntries = $this->records('08_entries_forecast_balance_by_year.csv');

        foreach ($forecastEntries as $forecastEntry) {
            /** @var array<string, mixed> $result */
            $result = $this->connection->fetchAssociative(
                'SELECT YEAR(e.created_at) as year, ROUND(SUM(e.amount), 3) as total_by_year
                        FROM entry e
                        WHERE e.budget_id IS NOT NULL
                          AND e.amount > 0
                          AND YEAR(e.created_at) = :targetYear
                        GROUP BY year
                        ORDER BY year',
                [
                    'targetYear' => $forecastEntry['year'],
                ]
            );

            $this->connection->insert('entry', [
                'budget_id' => 1275,
                'amount'    => ($forecastEntry['amount'] - $result['total_by_year']),
                'name'      => sprintf(
                    'Regularisation of %s',
                    $forecastEntry['year'],
                ),
                'created_at' => YearRange::firstDayOf(2017)
                    ->setTime(rand(0, 24), rand(0, 60), rand(0, 60))
                    ->format('c'),
                'updated_at' => (new DateTimeImmutable())->format('c'),
            ]);
        }
    }

    public function applyForecastForCurrentYear(): void
    {
        $forecasts = $this->connection->fetchAllAssociative(
            'SELECT b.id, b.name, ROUND(b.amount / 12, 3) as amount
                    FROM budget b
                    WHERE b.enable'
        );

        $currentMonthNumber = (int) (new DateTimeImmutable())->format('m');
        for ($monthNumber = 1; $monthNumber <= $currentMonthNumber; ++$monthNumber) {
            foreach ($forecasts as $forecast) {
                $firstDayOfMont = (new DateTimeImmutable())
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
                    'updated_at' => (new DateTimeImmutable())->format('c'),
                ]);
            }
        }

        $this->connection->insert('entry', [
            'budget_id'  => 1275,
            'amount'     => -928.05,
            'name'       => 'Regularisation',
            'created_at' => (new DateTimeImmutable())->format('c'),
            'updated_at' => (new DateTimeImmutable())->format('c'),
        ]);
    }
}
