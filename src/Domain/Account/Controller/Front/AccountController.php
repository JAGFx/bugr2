<?php

declare(strict_types=1);

namespace App\Domain\Account\Controller\Front;

use App\Domain\Account\Entity\Account;
use App\Domain\Account\Manager\AccountManager;
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
}
