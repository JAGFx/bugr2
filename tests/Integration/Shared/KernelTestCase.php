<?php

namespace App\Tests\Integration\Shared;

use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class KernelTestCase extends \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
{
    use Factories;
    use ResetDatabase;
}
