<?php

namespace App\Domain\Entry\Controller\Back;

use App\Domain\Entry\Entity\Entry;
use App\Domain\Entry\Form\EntryType;
use App\Domain\Entry\Manager\EntryManager;
use App\Domain\Entry\Model\EntrySearchCommand;
use App\Infrastructure\KnpPaginator\Controller\PaginationFormHandlerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/entries')]
class EntryController extends AbstractController
{
    use PaginationFormHandlerTrait;

    public function __construct(
        private readonly EntryManager $entryManager
    ) {
    }

    #[Route('/', name: 'back_entries_list', methods: Request::METHOD_GET)]
    public function list(Request $request): Response
    {
        $this->handlePaginationForm($request, $command = new EntrySearchCommand());

        return $this->render('domain/entry/index.html.twig', [
            'pagination' => $this->entryManager->getPaginated($command),
        ]);
    }

    #[Route('/create', name: 'back_entry_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request): Response
    {
        $form = $this
            ->createForm(EntryType::class, $entry = new Entry())
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entryManager->create($entry);

            return $this->redirectToRoute('back_entries_list');
        }

        return $this->render('domain/entry/form.html.twig', [
            'form' => $form,
        ]);
    }
}
