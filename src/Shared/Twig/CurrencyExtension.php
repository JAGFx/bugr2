<?php

namespace App\Shared\Twig;

use Twig\Attribute\AsTwigFilter;
use Twig\Extension\AbstractExtension;

class CurrencyExtension extends AbstractExtension
{
    #[AsTwigFilter('currency')]
    public function currency(float $amount, bool $withSign = true): string
    {
        if ($withSign) {
            $result = ($amount < 0)
                ? ''
                : '+';
        } else {
            $result = '';
        }

        $result .= number_format($amount, 2, '.', ' ');

        return $result . ' €';
    }
}
