<?php

namespace App\Domain\Budget\Controller\Front;

use App\Domain\Budget\Entity\Budget;
use App\Domain\Budget\Form\BudgetSearchType;
use App\Domain\Budget\Manager\BudgetManager;
use App\Domain\Budget\Model\Search\BudgetSearchCommand;
use App\Shared\Model\TurboResponseTraits;
use App\Shared\Utils\YearRange;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('budgets')]
class BudgetController extends AbstractController
{
    use TurboResponseTraits;

    public function __construct(
        private readonly BudgetManager $budgetManager
    ) {
    }

    #[Route('/progress-filter', name: 'front_budget_filter', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function filter(Request $request): Response
    {
        $command = (new BudgetSearchCommand())
            ->setShowCredits(false)
            ->setYear(YearRange::current());

        $form = $this
            ->createForm(BudgetSearchType::class, $command, [
                'action' => $this->generateUrl('front_budget_filter'),
            ])
            ->handleRequest($request);

        return $this->renderTurboStream(
            $request,
            'domain/budget/turbo/success.stream.progress_list.html.twig',
            [
                'form' => $form,
                'budgets' => $this->budgetManager->searchValueObject($command),
            ]
        );
    }

    #[Route('/{id}/disable', name: 'front_budget_disable', methods: [Request::METHOD_GET])]
    public function disable(Request $request, Budget $budget): Response
    {
        $this->budgetManager->disable($budget);

        return $this->renderTurboStream(
            $request,
            'domain/budget/turbo/success.stream.disable.html.twig',
            [
                'budget' => $budget,
            ]
        );
    }
}
