<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'phpdoc_to_comment' => false,
        'yoda_style' => true,
    ])
    ->setFinder($finder);