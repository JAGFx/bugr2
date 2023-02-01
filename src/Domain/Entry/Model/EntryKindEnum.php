<?php

namespace App\Domain\Entry\Model;

enum EntryKindEnum: string
{
    case DEFAULT   = 'default';
    case BALANCING = 'balancing';
}
