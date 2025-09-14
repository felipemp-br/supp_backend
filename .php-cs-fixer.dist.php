<?php

declare(strict_types = 1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('vendor');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'no_superfluous_phpdoc_tags' => false,
        'fully_qualified_strict_types' => true,
        'global_namespace_import' => false,
        'array_syntax' => ['syntax' => 'short'],
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
        ],
        'braces' => [
            'position_after_anonymous_constructs' => 'same',
            'position_after_control_structures' => 'same',
            'position_after_functions_and_oop_constructs' => 'next',
        ],
        'phpdoc_line_span' => ['property' => 'single', 'method' => 'single'],
        'phpdoc_indent' => true,
        'phpdoc_align' => [
            'align' => 'vertical',
            'tags' => ['param', 'return', 'throws'],
        ],
        'method_chaining_indentation' => true,
        'no_whitespace_in_blank_line' => true,
    ])
    ->setFinder($finder);
