<?php
    $config = new PhpCsFixer\Config();
    return $config->setRules([
        '@DoctrineAnnotation' => true,
        '@Symfony:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
        ->setRiskyAllowed(true)
    ;