<?php

namespace App\Infrastructure\KnpPaginator\Model;

interface PaginationInterface
{
    public function getPage(): int;

    public function getPageSize(): int;
}
