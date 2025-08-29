<?php

namespace App\Domain\Budget\Security;

use App\Domain\Budget\Entity\Budget;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, Budget>
 */
class BudgetVoter extends Voter
{
    public const string MANAGE = 'BUDGET_MANAGE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (self::MANAGE !== $attribute) {
            return false;
        }

        return $subject instanceof Budget;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        /** @var Budget $budget */
        $budget = $subject;

        return match ($attribute) {
            self::MANAGE => $this->canManage($budget),
            default      => throw new LogicException('This code should not be reached!'),
        };
    }

    private function canManage(Budget $budget): bool
    {
        return !$budget->isReadOnly();
    }
}
