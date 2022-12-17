<?php

namespace App\Domain\Entry\Controller\Front;

use App\Domain\Entry\Manager\EntryManager;
use App\Shared\Model\TurboResponseTraits;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/entries')]
class EntryController extends AbstractController
{
    use TurboResponseTraits;

    public function __construct(
        private readonly EntryManager $entryManager
    ) {
    }

    #[Route('/balance', name: 'front_entry_balance', methods: Request::METHOD_GET)]
    public function balance(Request $request): Response
    {
        return $this->renderTurboStream($request, 'domain/entry/turbo/success.stream.balance.html.twig', [
            'entryBalance' => $this->entryManager->balance(),
        ]);
    }
}
