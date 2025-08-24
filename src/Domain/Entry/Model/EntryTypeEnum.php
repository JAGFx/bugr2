<?php

namespace App\Domain\Entry\Model;

enum EntryTypeEnum: string
{
    case TYPE_FORECAST = 'type-forecast';
    case TYPE_SPENT    = 'type-spent';

    public function humanize(): string
    {
        return match ($this) {
            self::TYPE_FORECAST => 'Provision',
            self::TYPE_SPENT    => 'Dépense',
        };
    }
}
