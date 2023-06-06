<?php

$finder = PhpCsFixer\Finder::create()->in([__DIR__ . '/src', __DIR__ . '/tests']);

$config = new PhpCsFixer\Config();

$config->setRiskyAllowed(true)
    ->setRules(
    [
        '@PSR12' => true,
        '@PHP71Migration:risky' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
        'single_blank_line_at_eof' => true,
    ]
)->setFinder($finder);

return $config;
