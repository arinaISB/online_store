<?php

declare(strict_types=1);

use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use FOS\ElasticaBundle\FOSElasticaBundle;
use Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MakerBundle\MakerBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Twig\Extra\TwigExtraBundle\TwigExtraBundle;

return [
    FrameworkBundle::class => ['all' => true],
    DoctrineBundle::class => ['all' => true],
    DoctrineMigrationsBundle::class => ['all' => true],
    DebugBundle::class => ['dev' => true],
    TwigBundle::class => ['all' => true],
    WebProfilerBundle::class => ['dev' => true, 'test' => true],
    TwigExtraBundle::class => ['all' => true],
    SecurityBundle::class => ['all' => true],
    MonologBundle::class => ['all' => true],
    MakerBundle::class => ['dev' => true],
    LexikJWTAuthenticationBundle::class => ['all' => true],
    DAMADoctrineTestBundle::class => ['test' => true],
    FOSElasticaBundle::class => ['all' => true],
];
