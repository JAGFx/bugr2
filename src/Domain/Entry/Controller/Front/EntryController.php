<?php

namespace App\Domain\Entry\Controller\Front;

use App\Domain\Entry\Entity\Entry;
use App\Domain\Entry\Manager\EntryManager;
use App\Shared\Model\TurboResponseTraits;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/entries')]
class EntryController extends AbstractController
{
    use TurboResponseTraits;

    public function __construct(
        private readonly EntryManager $entryManager,
    ) {
    }

    #[Route('/balance', name: 'front_entry_balance', methods: Request::METHOD_GET)]
    public function balance(Request $request): Response
    {
        return $this->renderTurboStream($request, 'domain/entry/turbo/success.stream.balance.html.twig', [
            'entryBalance' => $this->entryManager->balance(),
        ]);
    }

    #[Route('/{id}/remove', name: 'front_entry_remove', methods: Request::METHOD_GET)]
    public function remove(Entry $entry, Request $request): Response
    {
        $entryId = $entry->getId();
        $this->entryManager->remove($entry);

        return $this->renderTurboStream($request, 'domain/entry/turbo/success.stream.remove.html.twig', [
            'entryId' => $entryId,
        ]);
    }

    protected function renderForm(string $view, array $parameters = [], ?Response $response = null): Response
    {
        throw new LogicException('Not implemented');
    }
}
