<?php

namespace App\Domain\PeriodicEntry\Controller\Front;

use App\Domain\PeriodicEntry\Entity\PeriodicEntry;
use App\Domain\PeriodicEntry\Manager\PeriodicEntryManager;
use App\Shared\Model\TurboResponseTraits;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/periodic_entries')]
class PeriodicEntryController extends AbstractController
{
    use TurboResponseTraits;

    public function __construct(
        private readonly PeriodicEntryManager $periodicEntryManager,
    ) {
    }

    #[Route('/{id}/remove', 'front_periodicentry_remove', requirements: ['id' => '\d+'], methods: Request::METHOD_GET)]
    public function remove(PeriodicEntry $periodicEntry, Request $request): Response
    {
        $periodicEntryId = $periodicEntry->getId();

        $this->periodicEntryManager->remove($periodicEntry);

        return $this->renderTurboStream($request, 'domain/periodic_entry/turbo/stream.success.remove.html.twig', [
            'periodicEntryId' => $periodicEntryId,
        ]);
    }
}
