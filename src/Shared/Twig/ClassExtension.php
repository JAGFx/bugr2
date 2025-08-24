<?php

declare(strict_types=1);

namespace App\Shared\Twig;

use Twig\Attribute\AsTwigFunction;

class ClassExtension
{
    #[AsTwigFunction('isInstanceOf')]
    public function isInstanceOf(mixed $object, string $className): bool
    {
        return $object instanceof $className;
    }
}
