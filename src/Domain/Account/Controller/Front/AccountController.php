<?php

declare(strict_types=1);

namespace App\Domain\Account\Controller\Front;

use App\Domain\Account\Entity\Account;
use App\Domain\Account\Manager\AccountManager;
use App\Domain\Entry\Manager\EntryManager;
use App\Domain\Entry\Model\EntrySearchCommand;
use App\Shared\Model\TurboResponseTraits;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('accounts')]
class AccountController extends AbstractController
{
    use TurboResponseTraits;

    public function __construct(
        private readonly AccountManager $accountManager,
        private readonly EntryManager $entryManager,
    ) {
    }

    #[Route('/{id}/toggle', name: 'front_account_toggle', methods: [Request::METHOD_GET])]
    public function toggle(Request $request, Account $account): Response
    {
        $this->accountManager->toggle($account);

        $message = 'Compte ';
        $message .= ($account->isEnable()) ? 'activé' : 'désactivé';

        $this->addFlash('success', $message);

        return $this->renderTurboStream(
            $request,
            'domain/account/turbo/success.stream.toggle.html.twig',
            [
                'account' => $account,
            ]
        );
    }

    #[Route('/{id}/cash-flow', name: 'front_account_cash_flow', methods: [Request::METHOD_GET])]
    public function cashFlow(Request $request, Account $account): Response
    {
        $entrySearchCommand = new EntrySearchCommand($account);
        $entryBalance       = $this->entryManager->balance($entrySearchCommand);

        return $this->renderTurboStream(
            $request,
            'domain/account/turbo/success.stream.cash_flow_account.html.twig',
            [
                'account'           => $account,
                'entryBalance'      => $entryBalance,
                'assignmentBalance' => $this->accountManager->getBalanceAssignments($account),
            ]
        );
    }
}
