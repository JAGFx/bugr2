<?php

declare(strict_types=1);

namespace App\Domain\Assignment\Controller\Back;

use App\Domain\Assignment\Manager\AssignmentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/assignments')]
class AssignmentController extends AbstractController
{
    public function __construct(
        private readonly AssignmentManager $assignmentManager,
    ) {
    }

    #[Route(name: 'back_assignment_list', methods: [Request::METHOD_GET])]
    public function index(): Response
    {
        return $this->render('domain/assigment/index.html.twig', [
            'assignments' => $this->assignmentManager->getAssignments(),
        ]);
    }
}
