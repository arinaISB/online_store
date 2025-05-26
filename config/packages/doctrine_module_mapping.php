<?php

declare(strict_types=1);

use Symfony\Component\Finder\Finder;
use Symfony\Config\DoctrineConfig;

return static function (DoctrineConfig $doctrineConfig): void {
    $finder = new Finder();
    $finder->files()->name('doctrine.php')->in(__DIR__ . '/../../src/**');

    foreach ($finder as $file) {
        $configurator = include $file;
        if (is_callable($configurator)) {
            $configurator($doctrineConfig);
        }
    }
};
