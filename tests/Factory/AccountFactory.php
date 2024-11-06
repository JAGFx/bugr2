<?php

namespace App\Tests\Factory;

use App\Domain\Account\Entity\Account;
use Zenstruck\Foundry\ModelFactory;

class AccountFactory extends ModelFactory
{
    protected static function getClass(): string
    {
        return Account::class;
    }

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->name(),
        ];
    }
}
