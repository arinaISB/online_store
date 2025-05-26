<?php

declare(strict_types=1);

use Symfony\Config\DoctrineConfig;

return static function (DoctrineConfig $doctrine): void {
    $emDefault = $doctrine->orm()->entityManager('default');

    $emDefault->autoMapping(true);
    $emDefault->mapping('Cart')
        ->type('attribute')
        ->dir(__DIR__ . '/Entity')
        ->isBundle(false)
        ->prefix('App\Cart\Entity')
        ->alias('Cart');
};
