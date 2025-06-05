<?php

declare(strict_types=1);

use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->alias(\FOS\ElasticaBundle\Finder\HybridFinderInterface::class, 'fos_elastica.finder.product');
};
