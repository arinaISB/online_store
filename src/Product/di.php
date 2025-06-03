<?php

declare(strict_types=1);

use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\Product\\', __DIR__ . '/{Service,Controller,Repository,EventListener}/*');

    $services->alias(PaginatedFinderInterface::class, 'fos_elastica.finder.product');
};
