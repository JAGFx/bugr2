<?php

namespace App\Domain\Entry\Model;

enum EntryKindEnum: string
{
    case DEFAULT = 'default';
    case BALANCING = 'balancing';

    public function humanize(): string {
        return match($this) {
          self::DEFAULT => 'Dépense',
          self::BALANCING => 'Provision'
        };
    }
}
