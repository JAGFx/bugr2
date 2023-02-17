<?php

namespace App\Tests\Factory;

use App\Domain\Budget\Entity\Budget;
use DateTimeImmutable;
use Zenstruck\Foundry\ModelFactory;

final class BudgetFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'amount'    => self::faker()->randomFloat(),
            'createdAt' => DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'enable'    => self::faker()->boolean(),
            'historic'  => [],
            'name'      => self::faker()->text(),
            'updatedAt' => DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    protected static function getClass(): string
    {
        return Budget::class;
    }
}
