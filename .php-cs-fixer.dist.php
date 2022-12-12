<?php
return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setFinder(
        PhpCsFixer\Finder::create()->in('src')
    )
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'heredoc_to_nowdoc' => true,
        'php_unit_strict' => true,
        'php_unit_construct' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_order' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'no_extra_blank_lines' => true,
        'echo_tag_syntax' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'semicolon_after_instruction' => true,
        'combine_consecutive_unsets' => true,
        'ternary_to_null_coalescing' => true,
    ]);