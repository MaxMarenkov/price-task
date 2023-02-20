<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->exclude('Migrations')
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PhpCsFixer' => true,
        '@PHP80Migration:risky' => true,
        '@PHP81Migration' => true,
        '@PSR12:risky' => true,
        '@PSR12' => true,
        '@Symfony:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
        'class_definition' => ['multi_line_extends_each_single_line' => true],
        'concat_space' => ['spacing' => 'one'],
        'echo_tag_syntax' => ['format' => 'long'],
        'linebreak_after_opening_tag' => true,
        'list_syntax' => ['syntax' => 'short'],
        'native_constant_invocation' => false,
        'native_function_invocation' => false,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'phpdoc_summary' => false,
        'psr_autoloading' => true,
        'php_unit_test_class_requires_covers' => false, // provided by @PhpCsFixer rule
        'php_unit_internal_class' => false, // provided by @PhpCsFixer rule
        'blank_line_before_statement' => [
            'statements' => [
                'break',
                'continue',
                'declare',
                'default',
                'exit',
                'goto',
                'include',
                'include_once',
                'phpdoc',
                'require',
                'require_once',
                'return',
                'switch',
                'throw',
                'try',
                'yield',
            ],
        ], // provided by @PhpCsFixer rule
        'global_namespace_import' => ['import_classes' => true, 'import_constants' => true, 'import_functions' => true],
    ])
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache')
    ->setFinder($finder)
;
