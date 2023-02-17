<?php

namespace App\Tests\Factory;

use App\Domain\Entry\Entity\Entry;
use App\Domain\Entry\Model\EntryKindEnum;
use DateTimeImmutable;
use Zenstruck\Foundry\ModelFactory;

final class EntryFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'amount'    => self::faker()->randomFloat(),
            'createdAt' => DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'kind'      => self::faker()->randomElement(EntryKindEnum::cases()),
            'name'      => self::faker()->text(),
            'updatedAt' => DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    protected static function getClass(): string
    {
        return Entry::class;
    }
}
