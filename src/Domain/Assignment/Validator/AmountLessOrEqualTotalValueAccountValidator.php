<?php

namespace App\Domain\Assignment\Validator;

use App\Domain\Assignment\Entity\Assignment;
use App\Domain\Entry\Manager\EntryManager;
use App\Domain\Entry\Model\EntrySearchCommand;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AmountLessOrEqualTotalValueAccountValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntryManager $entryManager,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof AmountLessOrEqualTotalValueAccount) {
            throw new InvalidArgumentException(sprintf('Expected instance of %s, got %s', AmountLessOrEqualTotalValueAccount::class, get_debug_type($constraint)));
        }

        if (!$value instanceof Assignment) {
            return;
        }

        $entrySearchCommand = new EntrySearchCommand($value->getAccount());
        $entryBalance       = $this->entryManager->balance($entrySearchCommand);

        if ($value->getAmount() > $entryBalance->getTotal()) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ total }}', number_format($entryBalance->getTotal(), 2, ',', ' '))
                ->setInvalidValue($value->getAmount())
                ->atPath('amount')
                ->addViolation();
        }
    }
}
