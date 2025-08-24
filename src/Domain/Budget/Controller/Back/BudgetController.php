<?php

namespace App\Domain\Budget\Controller\Back;

use App\Domain\Budget\Entity\Budget;
use App\Domain\Budget\Form\BudgetSearchType;
use App\Domain\Budget\Form\BudgetType;
use App\Domain\Budget\Manager\BudgetManager;
use App\Domain\Budget\Model\Search\BudgetSearchCommand;
use App\Domain\Budget\Operator\HistoryBudgetOperator;
use App\Shared\Model\ControllerActionEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/budgets')]
class BudgetController extends AbstractController
{
    public function __construct(
        private readonly BudgetManager $budgetManager
    ) {
    }

    #[Route('/', name: 'back_budget_budget_list', methods: Request::METHOD_GET)]
    public function list(Request $request, HistoryBudgetOperator $historyBudgetOperator): Response
    {
        $historyBudgetOperator->generateHistoryBudgetsForYear(2023);

        $budgetSearchCommand = new BudgetSearchCommand();

        $this
            ->createForm(BudgetSearchType::class, $budgetSearchCommand)
            ->submit($request->query->all());

        return $this->render('domain/budget/index.html.twig', [
            'budgets' => $this->budgetManager->search($budgetSearchCommand),
        ]);
    }

    #[Route('/create', name: 'back_budget_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request): Response
    {
        return $this->handleForm(ControllerActionEnum::CREATE, $request);
    }

    #[Route('/{id}', name: 'back_budget_edit', requirements: ['id' => '\d+'], methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function edit(Request $request, Budget $budget): Response
    {
        return $this->handleForm(ControllerActionEnum::EDIT, $request, $budget);
    }

    private function handleForm(ControllerActionEnum $action, Request $request, ?Budget $budget = null): Response
    {
        $budget ??= new Budget();

        $form = $this->createForm(BudgetType::class, $budget)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (ControllerActionEnum::CREATE === $action) {
                $this->budgetManager->create($budget);
            } else {
                $this->budgetManager->update();
            }

            return $this->redirectToRoute('back_budget_budget_list');
        }

        return $this->render('domain/budget/form.html.twig', [
            'form' => $form,
        ]);
    }
}
