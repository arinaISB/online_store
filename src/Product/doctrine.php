<?php

declare(strict_types=1);

use Symfony\Config\DoctrineConfig;

return static function (DoctrineConfig $doctrine): void {
    $emDefault = $doctrine->orm()->entityManager('default');

    $emDefault->autoMapping(true);
    $emDefault->mapping('Product')
        ->type('attribute')
        ->dir(__DIR__ . '/Entity')
        ->isBundle(false)
        ->prefix('App\Product\Entity')
        ->alias('Product');
};
