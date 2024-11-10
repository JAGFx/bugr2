<?php

declare(strict_types=1);

namespace App\Shared\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ClassExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('isInstanceOf', [$this, 'isInstanceOf']),
        ];
    }

    public function isInstanceOf(mixed $object, string $className): bool
    {
        return $object instanceof $className;
    }
}
