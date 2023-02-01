<?php

namespace App\Shared\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CurrencyExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('currency', [$this, 'currency']),
        ];
    }

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
