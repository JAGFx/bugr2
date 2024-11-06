<?php

namespace App\Domain\Budget\Controller\Front;

use App\Domain\Budget\Entity\Budget;
use App\Domain\Budget\Form\BudgetBalanceSearchType;
use App\Domain\Budget\Manager\BudgetManager;
use App\Domain\Budget\Model\Search\BudgetSearchCommand;
use App\Domain\Entry\Manager\EntryManager;
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
        private readonly BudgetManager $budgetManager,
        private readonly EntryManager $entryManager
    ) {
    }

    #[Route('/progress-filter', name: 'front_budget_filter', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function filter(Request $request): Response
    {
        $budgetSearchCommand = (new BudgetSearchCommand())
            ->setShowCredits(false)
            ->setYear(YearRange::current());

        $form = $this
            ->createForm(BudgetBalanceSearchType::class, $budgetSearchCommand, [
                'action' => $this->generateUrl('front_budget_filter'),
            ])
            ->handleRequest($request);

        return $this->renderTurboStream(
            $request,
            'domain/budget/turbo/success.stream.progress_list.html.twig',
            [
                'form'    => $form,
                'budgets' => $this->budgetManager->searchValueObject($budgetSearchCommand),
            ]
        );
    }

    #[Route('/{id}/toggle', name: 'front_budget_toggle', methods: [Request::METHOD_GET])]
    public function toggle(Request $request, Budget $budget): Response
    {
        $this->budgetManager->toggle($budget);

        $message = 'Budget ';
        $message .= ($budget->getEnable()) ? 'activé' : 'désactivé';

        $this->addFlash('success', $message);

        return $this->renderTurboStream(
            $request,
            'domain/budget/turbo/success.stream.toggle.html.twig',
            [
                'budget' => $budget,
            ]
        );
    }

    #[Route('/{id}/balancing', name: 'front_budget_balancing', methods: [Request::METHOD_GET])]
    public function balancing(Request $request, Budget $budget): Response
    {
        // TODO: See how to have a budget with multiple entru and adjustment of it
        $this->budgetManager->balancing($budget);

        $this->addFlash('success', 'Budget équilibré');

        return $this->renderTurboStream(
            $request,
            'domain/budget/turbo/success.stream.balancing.html.twig',
            [
                'budget'       => $budget,
                'entryBalance' => $this->entryManager->balance(),
            ]
        );
    }
}
