<?php

namespace App\Domain\Entry\Model;

use App\Domain\Account\Entity\Account;
use App\Infrastructure\KnpPaginator\Model\PaginableTrait;
use App\Infrastructure\KnpPaginator\Model\PaginationInterface;

class EntrySearchCommand implements PaginationInterface
{
    use PaginableTrait;

    public function __construct(
        private ?Account $account = null
    ) {
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): EntrySearchCommand
    {
        $this->account = $account;

        return $this;
    }
}
