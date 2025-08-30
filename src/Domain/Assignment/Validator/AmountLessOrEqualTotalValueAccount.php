<?php

namespace App\Domain\Assignment\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class AmountLessOrEqualTotalValueAccount extends Constraint
{
    public string $message = 'Ce montant doit être inférieur ou égale la valeur totale du compte ({{ total }}€)';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
