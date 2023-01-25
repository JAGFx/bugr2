<?php

namespace App\Infrastructure\KnpPaginator\Controller;

use App\Domain\Entry\Form\EntrySearchType;
use App\Infrastructure\KnpPaginator\Model\PaginationInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

trait PaginationFormHandlerTrait
{
    abstract protected function createForm(string $type, mixed $data = null, array $options = []): FormInterface;
    public function handlePaginationForm(Request $request, PaginationInterface $command): void {
        $this
            ->createForm(EntrySearchType::class, $command, [
                'method' => Request::METHOD_GET,
                'csrf_protection' => false
            ])
            ->submit($request->query->all(), false);
    }
}