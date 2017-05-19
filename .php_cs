<?php

use Symfony\CS\Config\Config;
use Symfony\CS\Finder\DefaultFinder;

$finder = PhpCsFixer\Finder::create()->in([
    'src',
    'tests',
]);

return PhpCsFixer\Config::create()
    ->setRules(array(
        '@PSR2' => true,
    ))
    ->setUsingCache(true)
    ->setFinder($finder);
