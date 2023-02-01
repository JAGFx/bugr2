<?php

namespace App\Domain\Entry\Model;

use App\Infrastructure\KnpPaginator\Model\PaginableTrait;
use App\Infrastructure\KnpPaginator\Model\PaginationInterface;

class EntrySearchCommand implements PaginationInterface
{
    use PaginableTrait;
}
