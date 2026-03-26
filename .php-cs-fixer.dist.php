<?php

if (PHP_SAPI !== 'cli') {
    die('This script supports command line usage only.');
}

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => true,
        'no_unused_imports' => true,
        'ordered_imports' => true,
        'single_quote' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('.Build')
            ->exclude('node_modules')
            ->exclude('var')
            ->in(__DIR__)
    );
