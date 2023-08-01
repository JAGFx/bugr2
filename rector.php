<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\CodingStyle\Rector\ClassConst\VarConstantCommentRector;
use Rector\CodingStyle\Rector\FuncCall\ConsistentImplodeRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\CodingStyle\Rector\Switch_\BinarySwitchToIfElseRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveDelegatingParentCallRector;
use Rector\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\EarlyReturn\Rector\If_\ChangeIfElseValueAssignToEarlyReturnRector;
use Rector\EarlyReturn\Rector\If_\RemoveAlwaysElseRector;
use Rector\EarlyReturn\Rector\StmtsAwareInterface\ReturnEarlyIfVariableRector;
use Rector\Naming\Rector\Class_\RenamePropertyToMatchTypeRector;
use Rector\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\Privatization\Rector\Property\ChangeReadOnlyPropertyWithDefaultValueToConstantRector;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Rector\Class_\EventListenerToEventSubscriberRector;
use Rector\Symfony\Set\SymfonySetList;
use Rector\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector;
use Rector\Visibility\Rector\ClassMethod\ExplicitPublicClassMethodRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/*.php',
    ]);

    $rectorConfig->importNames();

    /// Sets
    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::DEAD_CODE,
        SetList::NAMING,
        SetList::PRIVATIZATION,
        SetList::PHP_82,
    ]);

    // Symfony
    $rectorConfig->symfonyContainerXml(__DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml');

    $rectorConfig->sets([
        SymfonySetList::SYMFONY_62,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ]);

    // Doctrine
    $rectorConfig->sets([
        DoctrineSetList::DOCTRINE_CODE_QUALITY
    ]);

    // PHPUnit
    $rectorConfig->sets([
        PHPUnitSetList::PHPUNIT_91,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
        PHPUnitSetList::PHPUNIT_EXCEPTION,
        PHPUnitSetList::REMOVE_MOCKS,
        PHPUnitSetList::PHPUNIT_SPECIFIC_METHOD,
        PHPUnitSetList::PHPUNIT_YIELD_DATA_PROVIDER,
    ]);

    // PHPStan
    $rectorConfig->phpstanConfig(__DIR__ . '/phpstan.neon');

    // Rules
    $rectorConfig->rules([
        ExplicitPublicClassMethodRector::class,
        ChangeIfElseValueAssignToEarlyReturnRector::class,
        RemoveAlwaysElseRector::class,
        ReturnEarlyIfVariableRector::class,
    ]);

    // Remove rules
    $rectorConfig->skip([
        ArrayShapeFromConstantArrayReturnRector::class,
        BinarySwitchToIfElseRector::class => [__DIR__ . '/*/src/Repository/*'],
        CallableThisArrayToAnonymousFunctionRector::class,
        ChangeReadOnlyVariableWithDefaultValueToConstantRector::class,
        ChangeReadOnlyPropertyWithDefaultValueToConstantRector::class => [__DIR__ . '/*/src/Entity/*'],
        ConsistentImplodeRector::class,
        EventListenerToEventSubscriberRector::class,
        FinalizeClassesWithoutChildrenRector::class,
        NewlineAfterStatementRector::class,
        RemoveAlwaysTrueIfConditionRector::class,
        RemoveDelegatingParentCallRector::class,
        RenamePropertyToMatchTypeRector::class,
        RenameParamToMatchTypeRector::class,
        VarConstantCommentRector::class,
    ]);


    // Risky rules (uncomment them and launch rector with --dry-run to check that you did not do a potential mistake)
    // $rectorConfig->rules([
    //     RemoveAlwaysTrueIfConditionRector::class
    // ]);
};